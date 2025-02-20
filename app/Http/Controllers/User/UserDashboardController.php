<?php

namespace App\Http\Controllers\User;

use App\Models\Book;
use App\Models\ProductFavourite;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Product;
use App\Models\Package;
use App\Models\ProductView;
use App\Rules\UniquePhoneWithDialCode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BorrowedBook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserDashboardController extends Controller
{


    public function index()
    {
        $user = Auth::user();
        $borrowed_books = BorrowedBook::whereHas('book', function ($q) {
            $q->with('author', 'category')
                ->where('status', '10');
        })->where('user_id', $user->id)->with('book')->latest()->get();
        $viewed_books = ProductView::whereHas('book', function ($q) {
            $q->with('author', 'category')
                ->where('status', '10');
        })->where('user_id', $user->id)->with('book')->latest('updated_at')->get();
        $authorBooks = Product::where('user_id', $user->id)->latest();
        $fav_books = ProductFavourite::where('user_id', $user->id)->count();
        $readers = $user->productViews()->with('user', 'book')->latest('total_view')->get();

        $remainingDays  = 0 ;
        if (isset($user->currentUserPlan->expired_date)) {
            $currentPlanExpireDate = Carbon::parse($user->currentUserPlan->expired_date);
            $remainingDays = now()->diffInDays($currentPlanExpireDate);
        }
        $data = [
            'title'                  => 'Dashboard',
            'last_viewed_books'      => $viewed_books,
            'last_borrowed_books'    => $borrowed_books->take(10),
            'total_viewed_books'     => $user->viewed()->count(),
            'total_borrowed_books'   => $user->borrowed()->count(),
            'subscription_remaining' => $remainingDays,
            'user'                   => $user,
            'readers_count'          => $readers->count(),
            'my_books'               => $authorBooks->get(),
            'top_readers'            => $readers,
            'pending_books'          => $authorBooks->where('status', 0)->get(),
            'decline_books'          => $authorBooks->where('status', 30)->get(),
            'total_fav_books'        => $fav_books,
        ];
        return view('user.index', $data);
    }

    // Dashboard
    public function settings()
    {
        $data['title'] = 'Settings';
        $data['user'] = Auth::user();
        return view('user.settings', $data);
    }

    public function transactions()
    {
        if(auth()->user()->role_id == 1)
        {
        $transactions = Transaction::where('user_id', Auth::user()->id)->latest()->get();
        return view('user.transactions', compact('transactions'));
        } else {
            abort(404, 'Page not found');
        }

    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email," . $id,
            'phone' => ["required","unique:users,phone,{$id}", new UniquePhoneWithDialCode($id, $request->input('dial_code'))],

        ]);

        DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            if (!empty($request->phone)) {
                $user->dial_code = '+' . $request->dial_code;
                $user->phone = $request->phone;
                $user->country = $request->country;
                $user->country_code = $request->country_code;
            }
            if ($request->image) {
                $image = $request->file('image');
                $base_name = preg_replace('/\..+$/', '', $image->getClientOriginalName());
                $base_name = explode(' ', $base_name);
                $base_name = implode('-', $base_name);
                $base_name = Str::lower($base_name);
                $image_name = $base_name . "-" . uniqid() . "." . $image->getClientOriginalExtension();
                $file_path = 'uploads/user_profile';
                $image->move(public_path($file_path), $image_name);
                $user->image = $file_path . '/' . $image_name;
            }
            $user->save();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('User not updated !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('user.settings.index',['tab' => 2]);
        }
        DB::commit();
        Toastr::success(trans('User Updated Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('user.settings.index',['tab' => 2]);
    }

    public function passChange($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
        //     'old_pass' => ['required',
        //         function ($attribute, $value, $fail) use ($request) {
        //             if (!Hash::check($request->old_pass, auth()->user()->password)) {
        //                 $fail('The provided old password is incorrect.');
        //             }
        //         },
        //     ],
        //     'password' => 'required|different:old_pass|confirmed',
            'password'              => 'required|confirmed',
            'password_confirmation' => 'required_with:password',
        ]);
        // ], [
        //     'password.different' => 'Your new password must be different from the current password.',
        // ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return response()->json(['status' => 0, 'message' => $firstError], 200);
        }

        DB::beginTransaction();
        try {

            $user = User::find($id);
            $user->password = bcrypt($request->password);
            $user->save();

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 0, 'message' => 'User password not updated!'], 200);
            // Toastr::error(trans('User password not updated!'), 'Error', ["positionClass" => "toast-top-right"]);
            // return redirect()->route('user.settings.index');
        }
        DB::commit();
        return response()->json(['status' => 1, 'message' => 'User Password Updated Successfully!'], 200);
        // Toastr::success(trans('User Password Updated Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        // return redirect()->route('user.settings.index');
    }

    public function Delete($id, Request $request)
    {
        $request->validate([
            'delete' => 'required'
        ], [
            'delete.required' => 'Please fill the input field by "Delete"'
        ]);

        DB::beginTransaction();
        try {
            if ($request->delete == "Delete") {
                $user = User::find($id);
                // dependency condition
                if($user){
                    $user->plan()->delete();
                    $user->tickets()->delete();
                    $user->forumPostLikes()->delete();
                    $user->forumComments()->delete();
                    $user->forums()->delete();
                    $user->clubPosts()->delete();
                    $user->clubMembers()->delete();
                    $user->clubComments()->delete();
                    $user->bookReviews()->delete();
                    $user->blogPostLikes()->delete();
                    $user->blogComments()->delete();
                    $user->clubs()->delete();
                    $user->viewed()->delete();
                    $user->borrowed()->delete();
                    $user->favorites()->delete();
                    $user->products()->delete();
                    $user->delete();
                    Auth::logout();
                }
            }
            else
            {
                return response()->json(['status' => 0, 'message' => 'Please Write "Delete"'], 200);
                // Toastr::error(trans('Please Write "Delete"'), 'Error', ["positionClass" => "toast-top-right"]);
                // return redirect()->back();
            }

        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            return response()->json(['status' => 0, 'message' => 'An error occurred while processing the Account Delete!'], 200);
            // Toastr::error(trans('An error occurred while processing the Account Delete!'), 'Error', ["positionClass" => "toast-top-right"]);
            // return redirect()->back();
        }
        DB::commit();
        return response()->json(['status' => 1, 'message' => 'Your account has been deleted'], 200);
        // Toastr::success(trans('Your Account is Deleted'), 'Success', ["positionClass" => "toast-top-right"]);
        // return redirect()->route('home');
    }

    public function billing($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billing_name'   => "required",
            'billing_email'  => "required|email",
            'billing_phone'  => "required",
            'country'        => "required",
            'city'           => "required",
            'state'          => "required",
            'zipcode'        => "required",
            'type'           => "required",
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            return response()->json(['status' => 0, 'message' => $firstError], 200);
        }

        DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->billing_name         = $request->billing_name;
            $user->billing_email        = $request->billing_email;
            $user->billing_phone        = $request->billing_phone;
            $user->country              = $request->country;
            $user->address              = $request->address;
            $user->city                 = $request->city;
            $user->state                = $request->state;
            $user->zipcode              = $request->zipcode;
            $user->type                 = $request->type;
            $user->save();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 0, 'message' => 'Billing information not updated!'], 200);
        }
        DB::commit();
         return response()->json(['status' => 1, 'message' => 'Billing information updated successfully!'], 200);
    }


}
