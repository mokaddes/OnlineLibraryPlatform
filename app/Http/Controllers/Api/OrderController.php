<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentRequest;
use App\Models\Product;
use App\Traits\RepoResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use RepoResponse;
    public function index($getBalance = false)
    {
        $user = Auth::user();
        $bookIds = Product::where('user_id', $user->id)->pluck('id');
        $orders = Order::whereIn('product_id', $bookIds)->get();
        $amount = $orders->where('payment_status', 0)->sum('amount');
        $commission = $orders->where('payment_status', 0)->sum('commission');
        $balance = $amount - $commission;
        if ($getBalance) {
            return $balance;
        }
        return $this->apiResponse(1, 200, 'Order List', [],  ['orders' => $orders, 'amount' => $amount, 'commission' => $commission, 'balance' => $balance]);

    }

    public function paymentRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'phone' => 'required',
            'comment' => 'required|max:1000',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse('0', 400, $validator->errors()->first(), [], $validator->errors());
        }
        try {
            $amount = $this->index(true);
            if ($amount <= 0) {
                return $this->apiResponse(0, 400, 'You have no pending payment request.');
            }
            DB::beginTransaction();
            $user_id = Auth::id();
            $payment = new PaymentRequest();
            $payment->user_id = $user_id;
            $payment->email = $request->email;
            $payment->phone = $request->phone;
            $payment->amount = $amount;
            $payment->currency_symbol = '$';
            $payment->comment = $request->comment;
            $payment->payment_status = 'pending';
            $result = $payment->save();
            if ($result) {
                $bookIds = Product::where('user_id', $user_id)->pluck('id');
                $orders = Order::whereIn('product_id', $bookIds)->where('payment_status', 0)->get();
                foreach ($orders as $order) {
                    $order->payment_status = 2;
                    $order->save();
                }
            }

        } catch (\Exception $e) {
            DB::rollback();
            return $this->apiResponse(0, 500, 'Something is wrong.', $e->getMessage());
        }
        DB::commit();
        return $this->apiResponse(1, 200, 'Payment request has been successfully processed.');


    }
}
