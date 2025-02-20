<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Setting;
use App\Models\User;
use App\Models\Product;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class checkoutController extends Controller
{


    /**
     * @throws \Exception
     */
    public function __construct()
    {
        setConfigData();
    }

    public function index($id, $flag = null)
    {
        $title = "Checkout";
        if($flag == 'book') {
            $plan = Product::findOrFail($id);
            $checkout_for = "book";
        } else {
            $plan = Package::findOrFail($id);
            $checkout_for = "plan";
        }
        $user = User::where('id', Auth::user()->id)->first();

        return view('user.checkout', compact('plan', 'user', 'title', 'checkout_for'));
    }

    public function submit(Request $request, $id)
    {

        $billingData = $request->except(['_token']);
        if ($request->payment_gateway_id == 'select_2') {
            return redirect()->route('user.package.subscribe', ['id' => $id, 'billing_data' => $billingData]);
        } else {
            $message = 'Subscription failed. Please try again.';
            try {


                $paypal = new PayPalClient();
                $paypal->getAccessToken();

                $url = url('/user/subscribe/success');
                $packageId = $id;
                $billingData = http_build_query(['billingData' => $billingData]);
                $successUrl = "{$url}?package_id={$packageId}&status=successful&{$billingData}";
                $cancelUrl = "{$url}?package_id={$packageId}&status=fail";
                $user = Auth::user();
                if($request->billing_for == 'book')
                {
                    $book = Product::find($id);
                    $itemName = $book->title;
                    $productId = $book->id;
                    $price = $book->book_price;
                    $paymentAttributes = $this->getPaymentAttributes($productId, $user, $price, $itemName, $successUrl, $cancelUrl);
                    $response = $paypal->createOrder($paymentAttributes);
                    if (isset($response['id'])) {
                        $approveLink = collect($response['links'])->firstWhere('rel', 'approve')['href'];
                        return new RedirectResponse($approveLink);
                    }
                } else {
                    $package = Package::find($id);
                    $planId = $package->plan_id2;
                    $subscriptionAttributes = $this->getSubscriptionAttributes($planId, $user, $package, $successUrl, $cancelUrl);
                    $response = $paypal->createSubscription($subscriptionAttributes);
                    if (isset($response['status']) && $response['status'] === 'APPROVAL_PENDING') {
                        $approveLink = collect($response['links'])->firstWhere('rel', 'approve')['href'];
                        return new RedirectResponse($approveLink);
                    }
                }

                Toastr::success($message, 'Success');
            } catch (\Throwable $e) {
                Toastr::error($message, 'Error');
            }

            return back();
        }
    }


    /**
     * @param $planId
     * @param $user
     * @param $package
     * @param string $successUrl
     * @param string $cancelUrl
     * @return array
     */
    public function getSubscriptionAttributes($planId, $user, $package, string $successUrl, string $cancelUrl): array
    {
        return [
            'plan_id' => $planId,
            'start_time' => now()->addMinutes(5)->toIso8601String(),
            'subscriber' => [
                'name' => [
                    'given_name' => $user->name,
                    'surname' => $user->last_name,
                ],
                'email_address' => $user->email,
            ],
            'application_context' => [
                'brand_name' => $package->title,
                'locale' => 'en-US',
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'SUBSCRIBE_NOW',
                'payment_method' => [
                    'payer_selected' => 'PAYPAL',
                    'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED',
                ],
                'return_url' => $successUrl,
                'cancel_url' => $cancelUrl,
            ],
        ];
    }

public function getPaymentAttributes($productId, $user, $price, $itemName, string $successUrl, string $cancelUrl): array
{
    return [
        'intent' => 'CAPTURE',
        'purchase_units' => [
            [
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => $price,
                ],
                'description' => $itemName,
            ],
        ],
        'application_context' => [
            'brand_name' => $itemName,
            'locale' => 'en-US',
            'shipping_preference' => 'NO_SHIPPING',
            'user_action' => 'PAY_NOW',
            'return_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ],
    ];
}

    /**
     * @throws \Throwable
     */
    public function checkActiveSubscribe($subsId)
    {
        $paypal = new PayPalClient();
        $paypal->getAccessToken();
        $response = $paypal->showSubscriptionDetails($subsId);
        return $response['status'];
    }


}
