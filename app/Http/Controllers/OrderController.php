<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\PaymentRequest;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public $user;
    protected $order;
    public function __construct(Order $order)
    {
        $this->order     = $order;
    }

    public function index()
    {
        $user = Auth::user();
        $data['title'] = 'Order List';
        $data['og_title'] = '';
        $data['og_description'] = '';
        $data['og_image'] = '';
        $bookIds = Product::where('user_id', $user->id)->pluck('id');
        $data['orders'] = Order::whereIn('product_id', $bookIds)->get();
        return view('user.order.index', $data);
    }


    public function paymentRequest(Request $request)
    {
            DB::beginTransaction();
            $this->validate($request, [
                'email'     => 'required|email',
                'phone'     => 'required',
                'comment'   => 'required|max:1000',
            ]);
            try {
                $user_id = Auth::id();
                $payment               = new PaymentRequest();
                $payment->user_id      = $user_id;
                $payment->email        = $request->email;
                $payment->phone        = $request->phone;
                $payment->amount       = $request->amount;
                $payment->currency_symbol = '$';
                $payment->comment      = $request->comment;
                $payment->payment_status = 'pending';
                $result = $payment->save();
                if($result) {
                    $bookIds = Product::where('user_id', $user_id)->pluck('id');
                    $orders = Order::whereIn('product_id', $bookIds)->where('payment_status', 0)->get();
                    foreach ($orders as $order) {
                        $order->payment_status = 2;
                        $order->save();
                    }
                }
                // $title = Auth::user()->name . ' replies to your forum.';
                // $routeString = 'frontend.forum.details,' . $forum_comment->getForum->slug;
                // $this->saveUserNotification($title, $routeString, 'forum', 'user', $forum_comment->getForum->created_by);
                // $this->sendUserPushNotification([$forum_comment->getForum->created_by], 'Forum replies' ,$title, $routeString);

                // if($result) {
                //     $name = $forum_comment->getUser->name.' '.$forum_comment->getUser->last_name;
                //     $data = [
                //         'admin_email' => getSetting()->support_email,
                //         'template'    => 'forumQuestion',
                //         'subject'     => 'New Forum Comment Submitted!',
                //         'greeting'    => 'Hello, Admin,',
                //         'body'        => 'A new comment on Forum - "'.$forum_comment->getForum->title.'" has been received from user - '.$name.'. Please review and respond to the users comment as soon as possible.',
                //         'link'        => route('admin.forum.comment.index'),
                //         'msg'         => 'Click here to navigate to the Forum Comment Index',
                //         'thanks'      => 'Thank you and stay with ' . ' ' . config('app.name'),
                //         'site_url'    => route('home'),
                //         'footer'      => '0',
                //         'site_name'   => config('app.name'),
                //         'copyright'   => ' © ' . ' ' . Carbon::now()->format('Y') . config('app.name') . ' ' . 'All rights reserved.',
                //     ];
                //     // if ($settings->app_mode == 'live') {
                //         Mail::to($data['admin_email'])->send(new Allmail($data));
                //     // }
                // }
            } catch (\Exception $e) {
                DB::rollback();
                Toastr::error('An error occurred while processing your request', 'Error', ["positionClass" => "toast-top-right"]);
                return redirect()->back()->withInput($request->all());
            }
            DB::commit();
            Toastr::success(trans('Your Request submitted, Please wait for admin approval !'), 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->back();

    }
}
