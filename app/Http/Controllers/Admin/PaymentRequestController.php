<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class PaymentRequestController extends Controller
{
    public $user;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }


    public function index()
    {
        $data['title'] = 'Payment Request';
        $data['rows'] = PaymentRequest::latest()->get();
        return view('admin.payment_request.index', $data);
    }

    public function statusChange($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $package = PaymentRequest::findOrFail($id);
            $package->payment_status = $request->status;
            $result = $package->save();
            if($result) {
                $user_id = $package->user_id;
                $bookIds = Product::where('user_id', $user_id)->pluck('id');
                $orders = Order::whereIn('product_id', $bookIds)->where('payment_status', 2)->get();
                foreach ($orders as $order) {
                    if($request->status == 'paid') {
                        $order->payment_status = 1;
                    } else {
                        $order->payment_status = 3;
                    }
                    $order->save();
                }
            }
            DB::commit();
            if($request->status == 'paid') {
                Toastr::success('Payment has been successfully processed.', 'Success');
            } else {
                Toastr::success('Payment request has been rejected.', 'Success');
            }
            
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Something went wrong!', 'Failed');
            return redirect()->back();
        }
    }

}
