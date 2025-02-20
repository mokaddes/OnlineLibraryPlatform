<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\FlutterWaveController;
use App\Models\Package;
use App\Models\User;
use App\Traits\RepoResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class InAppPurchaseController extends Controller
{
    use RepoResponse;

    public function submit(Request $request)
    {
        $message = 'Your payment has been failed. Please try again.';
        $status = 0;
        $code = 500;
        $validate = Validator::make($request->all(), [
            'package_id' => 'required',
            'subscription_id' => 'required',
            'transaction_id' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->apiResponse($status, $code, "Validation Error", $validate->errors()->first(), '');
        }


        try {
            $billingData = $request->billingData;
            $package = Package::find($request->package_id);
            $user = User::find(auth()->guard('api')->id());
            $planId = $package->plan_id2;
            $customerIdToFind = ' ';
            $subscription_id = $request->subscription_id;
            $flutter = new FlutterWaveController();
            $user_plan = $flutter->saveUserPlan($user, $package, $planId, $customerIdToFind, $subscription_id, $billingData);
            $userId = $user->id;
            $userPlanId = $user_plan->id;
            $provider = 'In-app-purchase';
            $transaction_id = $request->transaction_id ?? uniqid('trx_');
            $flutter->saveTransaction($userId, $userPlanId, $transaction_id, $package, $provider, $billingData);
            $status = 1;
            $code = 201;
            $message = 'Your payment has been successfully processed';
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
}
