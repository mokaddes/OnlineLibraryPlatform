<?php

namespace App\Http\Controllers\Paypal;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PayPal\Api\Agreement;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalWebhookController extends Controller
{
    use ApiResponse;
    protected $_api_context;
    protected $config;
    // PayPal
    public function __construct()
    {

        $this->config = getConfig();
        $config = $this->config;
        $this->_api_context = new ApiContext(new OAuthTokenCredential($config[4]->config_value, $config[5]->config_value));
        $this->_api_context->setConfig(array(
            'mode' => $config[3]->config_value,
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path() . '/logs/paypal.log',
            'log.LogLevel' => 'DEBUG',
        ));
    }

    public function handleWebhook(Request $request)
    {
        try {
            $headers = getallheaders();

            Log::debug('getallheaders',[$headers]);
            $config = $this->config;
            Log::info("Paypal is working fine!");
            $PAYPAL_WEBHOOK_ID = $config[36]->config_value;

            $body = json_decode($request->getContent(), true);
            $eventType = $body['event_type'];
            Log::info($eventType);
            $webhookEventResource = $body['resource'];
            switch ($eventType) {
                case 'PAYMENT.SALE.COMPLETED':
                    $this->recurringPaymentSuccess($webhookEventResource);
                    break;
                case 'BILLING.SUBSCRIPTION.ACTIVATED':
                    $subscription= $webhookEventResource;
                    // $this->billingSubscriptionActive($subscription);

                    break;
                case 'BILLING.SUBSCRIPTION.UPDATED':
                    $subscription= $webhookEventResource;
                    $this->updateSubscription($subscription);

                    break;
                case 'BILLING.SUBSCRIPTION.RENEWED':
                    // Handle subscription activated event
                    $subscription= $webhookEventResource;
                    // $this->updateSubscription($subscription);
                    break;
                case 'BILLING.SUBSCRIPTION.CREATED':
                    // Handle subscription activated event
                    $subscription= $webhookEventResource;
                    // $this->subscriptionCreate($subscription);
                    break;
                // Add other event types and handlers as needed
            }
            $this->postEventLog($webhookEventResource,$eventType);
        }
        catch (\Exception $e) {
            // dd($e->getMessage());
            Log::debug('Webhook exception1',[$e->getMessage()]);
            return $this->successResponse(200, 'Webhook not working.', '', 0);
        }
        return $this->successResponse(200, 'Webhook is working fine.', [], 1);
    }


    public function updateSubscription($subscription){
        // DB::beginTransaction();
        try {
            if(!empty($subscription->id)){
                $billing_agreement_id = $subscription->id;
                $user = User::where('subscription_id',$billing_agreement_id)->first();
                // $social_plan = Plan::findOrFail($user->plan_id);
                //Subscription data
                $agreement = Agreement::get($billing_agreement_id, $this->_api_context);
                $plan = $agreement->getPlan();
                $payment = $plan->getPaymentDefinitions()[0];
                $payer = $agreement->getPayer();
                $payer_info = $payer->getPayerInfo();
                $payer_id = $payer_info->payer_id;
                $agreementDetails = $agreement->getAgreementDetails();
                $nextBillingDate = $agreementDetails->getNextBillingDate();
                $startDate = $agreement->getStartDate();
                $activation_date =  Carbon::parse($startDate)->format('Y-m-d h:i:s');
                $nextbilled_date =  Carbon::parse($nextBillingDate)->format('Y-m-d h:i:s');

                //user plan information
                //$user->update();
            }
        }
        catch (\Exception $e) {

            Log::debug('BILLING.SUBSCRIPTION.UPDATED Exception',[$e->getMessage()]);
            // DB::rollback();
            return $this->successResponse(200, 'Unable to updated subscription !', '', 0);
        }
        // DB::commit();
        return $this->successResponse(200, 'Customer subscription updated !', [], 1);
    }


    public function recurringPaymentSuccess($invoice){
        // DB::beginTransaction();
        try {
            $invoice =json_decode(json_encode((object) $invoice), FALSE);
            Log::debug('json_encode',[$invoice]);
            // json_decode($invoice);
            $invoice_details = [];
            $invoice_number = Transaction::max('id')+1;
            $invoice_number = $invoice_number+1000;
            $config = $this->config;
            $billing_agreement_id = $invoice->billing_agreement_id;

            if(!empty($billing_agreement_id)){
                Log::debug('billing_agreement_id',[$billing_agreement_id]);
                $user = User::where('subscription_id',$billing_agreement_id)->first();
                $social_plan = SocialPlans::findOrFail($user->plan_id);
                Log::debug('user plan id',[$user->plan_id]);
                //Subscription data
                $agreement = Agreement::get($billing_agreement_id, $this->_api_context);
                $plan = $agreement->getPlan();
                $payment = $plan->getPaymentDefinitions()[0];
                $payer = $agreement->getPayer();
                $payer_info = $payer->getPayerInfo();
                $payer_id = $payer_info->payer_id;
                $agreementDetails = $agreement->getAgreementDetails();
                $nextBillingDate = $agreementDetails->getNextBillingDate();
                $startDate = $agreement->getStartDate();
                $activation_date =  Carbon::parse($startDate)->format('Y-m-d h:i:s');
                $nextbilled_date =  Carbon::parse($nextBillingDate)->format('Y-m-d h:i:s');

                // $transaction = new Transaction();
                //Transaction Information
                // $transaction->save();

                // $user->plan_validity = $nextbilled_date;
                // $user->update();
            }
        }
        catch (\Exception $e) {
            // dd($e->getMessage());
            Log::debug('recurringPaymentSuccess Exception',[$e->getMessage()]);
            // DB::rollback();
            return $this->successResponse(200, 'Unable to create Transection !', '', 0);
        }
        // DB::commit();
        Log::debug('recurringPaymentSuccess',[$transaction]);
        //Mail to user
        return $this->successResponse(200, 'Transection has been created !', [], 1);
    }

    public function subscriptionCreate($invoice){
        // Log::debug('subscriptionCreate',[$invoice]);
        // DB::beginTransaction();
        try {
            $invoice_details = [];
            $invoice_number = Transaction::max('id')+1;
            $invoice_number = $invoice_number+1000;
            $config = $this->config;

            if(!empty($invoice->id)){
                $billing_agreement_id = $invoice->id;
                $user = User::where('subscription_id',$billing_agreement_id)->first();
                $social_plan = SocialPlans::findOrFail($user->plan_id);
                //Subscription data
                $agreement = Agreement::get($billing_agreement_id, $this->_api_context);
                $plan = $agreement->getPlan();
                $payment = $plan->getPaymentDefinitions()[0];
                $payer = $agreement->getPayer();
                $payer_info = $payer->getPayerInfo();
                $payer_id = $payer_info->payer_id;
                $agreementDetails = $agreement->getAgreementDetails();
                $nextBillingDate = $agreementDetails->getNextBillingDate();
                $startDate = $agreement->getStartDate();
                $activation_date =  Carbon::parse($startDate)->format('Y-m-d h:i:s');
                $nextbilled_date =  Carbon::parse($nextBillingDate)->format('Y-m-d h:i:s');

                // $transaction = new Transaction();
                //Transaction Information
                // $transaction->save();

                // $user->plan_validity = $nextbilled_date;
                // $user->update();
            }
        }
        catch (\Exception $e) {
            // dd($e->getMessage());
            Log::debug('Webhook Exception',[$e->getMessage()]);
            // DB::rollback();
            return $this->successResponse(200, 'Unable to create Transection !', '', 0);
        }
        // DB::commit();
        return $this->successResponse(200, 'Transection has been created !', [], 1);
    }


    public function billingSubscriptionActive($subscription){
        DB::beginTransaction();
        try {
            Log::debug('Webhook data',[$subscription]);

            $config = $this->config;
            if(!empty($subscription->id)){
                $billing_agreement_id = $subscription->id;
                $user = User::where('subscription_id',$billing_agreement_id)->first();
                // $social_plan = Plan::findOrFail($user->plan_id);
                //Subscription data
                $agreement = Agreement::get($billing_agreement_id, $this->_api_context);
                $plan = $agreement->getPlan();
                $payment = $plan->getPaymentDefinitions()[0];
                $payer = $agreement->getPayer();
                $payer_info = $payer->getPayerInfo();
                $payer_id = $payer_info->payer_id;
                $agreementDetails = $agreement->getAgreementDetails();
                $nextBillingDate = $agreementDetails->getNextBillingDate();
                $startDate = $agreement->getStartDate();
                $activation_date =  Carbon::parse($startDate)->format('Y-m-d h:i:s');
                $nextbilled_date =  Carbon::parse($nextBillingDate)->format('Y-m-d h:i:s');

                // $user->plan_validity = $nextbilled_date;
                // $user->update();
            }
        }
        catch (\Exception $e) {
            // dd($e->getMessage());
            Log::debug('Webhook Exception',[$e->getMessage()]);
            DB::rollback();
            return $this->successResponse(200, 'Unable to create Transection !', '', 0);
        }
        DB::commit();
        return $this->successResponse(200, 'Transection has been created !', [], 1);
    }


    public function postEventLog($data,$eventType){
        DB::table('event_logs')->insert(
            [
                'payment_method'=>'paypal',
                'event_type'=>$eventType,
                'event_body'=>json_encode($data),
                'created_at'=>date('Y-m-d H:i:s'),
                'status'=>1,
            ]
        );
    }
}
