<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\checkoutController;
use App\Http\Controllers\User\FlutterWaveController;
use App\Models\BorrowedBook;
use App\Models\Order;
use App\Models\Package;
use App\Models\PaymentRequest;
use App\Models\Product;
use App\Models\User;
use App\Traits\RepoResponse;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Srmklive\PayPal\Facades\PayPal;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    use RepoResponse;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        setConfigData();
    }


    public function submit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required_if:payment_method,flutterwave',
            'billing_for' => 'required|in:plan,book',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse('0', 400, $validator->errors()->first(), [], $validator->errors());
        }

        try {


            $package = Package::find($id);
            if (!$package) {
                return $this->apiResponse(0, 404, 'Data not found.');
            }
            $user = Auth::guard('api')->user();
            if ($package->price == 0) {
                $transaction_id = uniqid('trx_');
                $flutter = new FlutterWaveController();
                $user_plan = $flutter->saveUserPlan($user, $package, 0, '', '');
                $flutter->saveTransaction($user->id, $user_plan->id, $transaction_id, $package, ' ');
                return $this->apiResponse(1, 200, 'Your package has been successfully processed', '');

            }
            if ($request->payment_method == 'flutterwave') {
                return $this->flutterSubmit($request, $package, $user);
            }
            $userId = $user->id;
            $billingData = $request->except(['_token']);
            $paypal = new PayPalClient();
            $paypal->getAccessToken();
            $url = url('/api/paypal/success');
            $packageId = $id;
            $billingData = http_build_query(['billingData' => $billingData]);
            $successUrl = "{$url}?package_id={$packageId}&user_id={$userId}&status=successful&{$billingData}";
            $cancelUrl = "{$url}?package_id={$packageId}&status=fail";

            $checkout = new checkoutController();

            $planId = $package->plan_id2;

            $subscriptionAttributes = $checkout->getSubscriptionAttributes($planId, $user, $package, $successUrl, $cancelUrl);
            $response = $paypal->createSubscription($subscriptionAttributes);
            if (isset($response['status']) && $response['status'] === 'APPROVAL_PENDING') {
                $link = collect($response['links'])->firstWhere('rel', 'approve')['href'];
                $data = [
                    'ApproveLink' => $link,
                    'response' => $response
                ];
                return $this->apiResponse(1, 200, 'Data successfully found.', '', $data);
            }


        } catch (\Throwable $e) {
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse(0, 500, 'Something is wrong.', $e->getMessage(), '', $errorArray);
        }

    }

    public function flutterSubmit(Request $request, $package, $user)
    {

        try {
            $flutter = new FlutterWaveController();
            $billingData = $request->all();
            $data = $flutter->updateUserPlan($request, $package, $billingData);
            if ($data === null) {
                DB::rollBack();
                return $this->apiResponse(1, 200, 'Your payment has been failed. Please try again.', '', $data);
            }
            $userId = $data['user_id'];
            $userPlanId = $data['user_plan_id'];
            $provider = 'flutterwave';
            $transaction_id = $request->transaction_id ?? uniqid('trx_');
            $flutter->saveTransaction($userId, $userPlanId, $transaction_id, $package, $provider, $billingData);
        } catch (\Exception $e) {
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse(0, 500, 'Your payment has been failed. Please try again.', 'Provided transaction id is not valid!', $errorArray);
        }
        return $this->apiResponse(1, 200, 'Your payment has been successfully processed.', '', $data);

    }

    public function success(Request $request)
    {
        $message = 'Your payment has been failed. Please try again.';
        $status = 0;
        $code = 500;
        try {
            $billingData = $request->billingData;
            $flutter = new FlutterWaveController();

            $user = User::find($request->user_id);
            $transaction_id = $request->transaction_id ?? uniqid('trx_');

            $package = Package::find($request->package_id);
            $planId = $package->plan_id2;

            $customerIdToFind = ' ';
            $subscription_id = $request->subscription_id;
            $res = $this->checkActiveSubscribe($subscription_id);
            if ($res == 'ACTIVE') {
                $user_plan = $flutter->saveUserPlan($user, $package, $planId, $customerIdToFind, $subscription_id, $billingData);
                $userId = $user->id;
                $userPlanId = $user_plan->id;
                $provider = 'paypal';
                $flutter->saveTransaction($userId, $userPlanId, $transaction_id, $package, $provider, $billingData);
                $status = 1;
                $code = 201;
                $message = 'Your payment has been successfully processed';
            }
        } catch (\Exception $e) {
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse($status, $code, $message, $e->getMessage(), '', $errorArray);

        }

        return $this->apiResponse($status, $code, $message);

    }

    /**
     * @throws \Throwable
     */
    public function checkActiveSubscribe($subsId)
    {
        $paypal = new PayPalClient();
        $paypal->getAccessToken();
        $response = $paypal->showSubscriptionDetails($subsId);
        return $response['status'] ?? '';
    }

    public function bookBuy(Request $request, $id)
    {
        $message = 'Your payment has been failed. Please try again.';
        $status = 0;
        $code = 500;
        try {
            $book = Product::find($id);
            $user = Auth::user();
            if (!$book) {
                return $this->apiResponse(0, 404, 'Data not found.');
            }
            $borrowed = BorrowedBook::where('user_id', $user->id)->where('product_id', $book->id)->where('is_bought', 1)->exists();
            if ($borrowed) {
                return $this->apiResponse(0, 400, 'You have already bought this book.');
            }
            $billingData = $request->except('_token');
            $billingData['billing_for'] = 'book';
            $flutter = new FlutterWaveController();
            $transaction_id = $request->transaction_id ?? uniqid('trx_');

            $flutter->saveBook($user, $book, $book->id, $transaction_id);
            $flutter->saveTransaction($user->id, $book->id, $transaction_id, $book, 'paypal', $billingData);
            return $this->apiResponse(1, 200, 'Your payment has been successfully processed');

        } catch (\Exception $e) {
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse($status, $code, $message, $e->getMessage(), '', $errorArray);

        }


    }

    public function paymentRes(Request $request)
    {
        $url = $request->get('url');
        $response = Http::get($url);

        if ($response->successful()) {
            $responseData = $response->body();
            return response($responseData);
        } else {
            return $this->apiResponse(0, 500, 'Your payment has failed. Please try again.');
        }
    }


}
