<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\UserPlan;
use App\Models\BorrowedBook;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Flutterwave\Service\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Psr\Http\Client\ClientExceptionInterface;
use Illuminate\Support\Facades\Validator;

class FlutterWaveController extends Controller
{


    public function subscribe(Request $request, $id)
    {
        $billingData = $request->billing_data;
        $url = url('/user/subscribe/success');
        $packageId = $id;
        $billingData = http_build_query(['billingData' => $billingData]);


        $fullUrl = "{$url}?package_id={$packageId}&{$billingData}";
        $package = Package::find($id);
        $user = Auth::user();
        if ($package->price == 0) {
            $transaction_id = uniqid('trx_');
            $user_plan = $this->saveUserPlan($user, $package, 0, '', '');
            $this->saveTransaction($user->id, $user_plan->id, $transaction_id, $package, ' ');
            Toastr::success('Your package has been successfully processed', 'Success');
            return redirect()->route('user.dashboard');
        }
        return view('frontend.redirecting', compact('package', 'billingData', 'fullUrl'));
    }

    /**
     * @param $user
     * @param $package
     * @param $planId
     * @param $customerIdToFind
     * @param $subscription_id
     * @param array|null $billingData
     * @return UserPlan
     */
    public function saveUserPlan($user, $package, $planId, $customerIdToFind, $subscription_id, ?array $billingData = []): UserPlan
    {
        if (isset($user->plan) && $user->plan()->count() > 0) {
            $user->plan()->update(['status' => 0]);
        }
        $user_plan = new UserPlan();
        $user_plan->user_id = $user->id;
        $user_plan->package_id = $package->id ?? 1;
        $user_plan->plan_id = $planId;
        $user_plan->customer_id = $customerIdToFind ?? '';
        $user_plan->subscription_id = $subscription_id ?? '';
        $user_plan->expired_date = $planId == 0 ? Carbon::now()->addYears(12) : Carbon::now()->addDays($package->duration ?? 30);
        $user_plan->status = 1;
        $user_plan->save();

        $user->plan_id = $package->id ?? 1;
        if (!empty($billingData)) {
            $dialCode = $billingData['billing_dial_code'] ?? $billingData['dial_code'] ?? $billingData['country_selector_code'] ?? '';
            $dialCode = ltrim($dialCode, '+');
            $phone = $billingData['billing_phone'] ?? '';
            $user->billing_name = $billingData['billing_name'] ?? '';
            $user->billing_email = $billingData['billing_email'] ?? '';
            $user->address = $billingData['billing_address'] ?? '';
            $user->city = $billingData['billing_city'] ?? '';
            $user->state = $billingData['billing_state'] ?? '';
            $user->zipcode = $billingData['billing_zipcode'] ?? '';
            $user->country = $billingData['billing_country'] ?? $user->country;
            $user->billing_dial_code = '+' . $dialCode;
            $user->billing_country_code = $billingData['billing_country_code'] ?? '';
            $user->type = $billingData['type'] ?? '';
            $user->billing_phone = $phone;
        }
        $user->save();
        return $user_plan;
    }
    public function saveBook($user, $book, $bookId, $transaction_id)
    {

        $commissionPercentage = getSetting()->commission;
        $bookPrice = $book->book_price;
        $commission = ($bookPrice * $commissionPercentage) / 100;

        $order = new Order();
        $order->product_id = $bookId;
        $order->product_name = $book->title;
        $order->trx_id = $transaction_id;
        $order->amount = $book->book_price;
        $order->commission = $commission;
        $order->order_by = $user->id;
        $order->payment_status = 0;
        $order->save();

        $borrowed = new BorrowedBook();
        $borrowed->fill([
            'product_id' => $bookId,
            'user_id' => $user->id,
            'is_valid' => 1,
            'is_institution' => 0,
            'is_bought' => 1,
            'borrowed_startdate' => now(),
            'borrowed_enddate' => null,
            'borrowed_nextdate' => null,
        ])->save();

        return $order;
    }
    /**
     * @param $userId
     * @param $userPlanId
     * @param $transaction_id
     * @param $package
     * @param $provider
     * @param null $billingData
     * @return void
     */
    public function saveTransaction($userId, $userPlanId, $transaction_id, $package, $provider, $billingData = null): void
    {
        $transaction = new Transaction();
        $transaction->user_id = $userId;
        $transaction->transaction_id = $transaction_id;

        if(isset($billingData['billing_for']) && $billingData['billing_for'] == 'book')
        {
            $bookId = $userPlanId;
            $transaction->book_id = $bookId;
            $transaction->amount = 0.00;
            $transaction->usd_amount = $package->book_price;
        }
        else {
            $transaction->user_plan_id = $userPlanId;
            $transaction->package_id = $package->id;
            $transaction->amount = $package->price_ngn;
            $transaction->usd_amount = $package->price;
        }

        $transaction->billing_data = json_encode($billingData, true);
        $transaction->currency_symbol = $provider == 'flutterwave' ? 'NGN' : 'USD';
        $transaction->payment_provider = $provider;
        $transaction->payment_status = 'paid';
        $transaction->save();
    }

    public function subscribeSuccess(Request $request)
    {
        try {
            DB::beginTransaction();

            if ($request->status == 'successful') {
                $billingData = $request->billingData;
                $transaction_id = $request->transaction_id ?? uniqid('trx_');

                if($billingData['billing_for'] == 'book')
                {
                    $book = Product::find($request->package_id);
                } else {
                    $package = Package::find($request->package_id);
                }

                if ($billingData['payment_gateway_id'] == 'select_2') {
                    $data = $this->updateUserPlan($request, $package, $billingData);
                    if ($data === null) {
                        DB::rollBack();
                        Toastr::error('Your payment was not successful. Please try again.', 'Error');
                        return redirect()->route('frontend.pricing');
                    }
                    $userId = $data['user_id'];
                    $userPlanId = $data['user_plan_id'];
                    $provider = 'flutterwave';
                } else {
                    $user = Auth::user();
                    if($billingData['billing_for'] == 'book')
                    {
                        $bookId = $book->id;

                    } else {
                        $planId = $package->plan_id2;

                    }
                    $customerIdToFind = ' ';

                    if($billingData['billing_for'] == 'book')
                    {
                        $order = $this->saveBook($user, $book, $bookId, $transaction_id);

                    } else {
                        $subscription_id = $request->subscription_id;
                        $user_plan = $this->saveUserPlan($user, $package, $planId, $customerIdToFind, $subscription_id, $billingData);
                        $userPlanId = $user_plan->id;
                    }

                    $userId = $user->id;
                    $provider = 'paypal';
                }

                if($billingData['billing_for'] == 'book')
                {
                    $this->saveTransaction($userId, $bookId, $transaction_id, $book, $provider, $billingData);
                } else {
                    $this->saveTransaction($userId, $userPlanId, $transaction_id, $package, $provider, $billingData);
                }
            } else {
                DB::rollBack();
                Toastr::error('Your payment was not successful. Please try again.', 'Error');
                return redirect()->route('frontend.pricing');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::alert($e->getMessage());
            Toastr::error('Your payment has failed. Please try again.', 'Error');
            return redirect()->route('frontend.pricing');
        }

        Toastr::success('Your payment has been successfully processed', 'Success');
        return redirect()->route('user.dashboard');
    }

    public function updateUserPlan($request, $package, $billingData = null)
    {

        try {
            $apiKey = env('SECRET_KEY');
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])
                ->get("https://api.flutterwave.com/v3/transactions/{$request->transaction_id}/verify");
            $result = $response->json();
            $email = $result['data']['customer']['email'] ?? '';
            if ($email && $result['data']['amount'] == $package->price_ngn) {
                $subs = new Subscription();
                $sub_list = $subs->list();

                $customerIdToFind = $result['data']['customer']['id'];
                $planId = $result['data']['plan'];

                $subscription_id = '';

                foreach ($sub_list->data as $sub) {
                    if (isset($sub->customer->id) && $sub->customer->id === $customerIdToFind && $sub->plan === $planId) {
                        $subscription_id = $sub->id;
                    }
                }
                $user = User::where('email', $email)->first();
                $user_plan = $this->saveUserPlan($user, $package, $planId, $customerIdToFind, $subscription_id, $billingData);

                return [
                    'user_id' => $user_plan->user_id,
                    'user_plan_id' => $user_plan->id,
                ];
            }
        } catch (ClientExceptionInterface $e) {
            Log::alert($e->getMessage());
        }
        return null;


    }


    public function subscribeCancel()
    {
        $subscriptionId = Auth::user()->plan->subscription_id ?? null;
        if ($subscriptionId) {
            $subs = new Subscription();
            $subscription = $subs->deactivate($subscriptionId);
            if ($subscription->status == 'success') {
                Toastr::success('Subscription deleted successfully', 'success');

            } else {
                Toastr::error('Something is wrong!', 'error');
            }
        } else {
            Toastr::error('You are not a subscriber!', 'Error');
        }

        return redirect()->back();

    }

}
