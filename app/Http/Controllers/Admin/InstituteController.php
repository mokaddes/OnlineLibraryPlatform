<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\FlutterWaveController;
use App\Http\Controllers\User\ReaderBookController;
use App\Models\BorrowedBook;
use App\Models\Package;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\UserRegisterMailNotification;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class InstituteController extends Controller
{
    use \App\Traits\Notification;

    public $user;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.institute.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Institute';
        $users = User::where('role_id', 3)->latest()->get();
        $data['rows'] = $users;

        $books = Product::where('status', 10)->get();
        return view('admin.institute.index', compact('data', 'books'));
    }

    public function view($id, Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.institute.view')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Institute';
        $user = User::with(['borrowed'])->where('id', $id)->first();
        $data['role'] = 'Institute';
        $data['row'] = $user;
        $product_count = $user->borrowed()->where('is_valid', 1)->count();
        return view('admin.institute.view', compact('data', 'product_count'));
    }


    public function create(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.institute.create')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }
        $data['role'] = $request->role;
        $data['title'] = 'Institute';
        $data['packages'] = Package::all();
        return view('admin.institute.create', compact('data'));
    }


    public function store(Request $request)
    {

//        if (is_null($this->user) || !$this->user->can('admin.institute.store')) {
//            abort(403, 'Sorry !! You are Unauthorized.');
//        }

        $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email",
            'password' => "required|confirmed|min:8|max:50",
        ]);

        DB::beginTransaction();

        try {
            $user = new User();
            $user->role_id = 3;
            $user->name = $request->name;
            $user->last_name = $request->last_name;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->image = 'assets/images/default-user.png';
            $user->email_verified_at = now();
            $user->save();

            $package = Package::where('price', 0)->first();
            $user_plan = new FlutterWaveController();
            $user_plan->saveUserPlan($user, $package, 0, '', '');

            $this->sendWelcomeEmail($request, $user);

            try {
                $setting = Setting::first();
                $email = $user->email;
                subscribeToMailchimp($setting, $email, $user->name, $user->last_name, 'Institute');
            } catch (\Exception $e) {
                Log::alert($e->getMessage());
            }
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Institute not Created !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back()->withInput();
        }
        DB::commit();
        Toastr::success('Institute Created Successfully', 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.institute.index');
    }

    public function sendWelcomeEmail($request, $User)
    {

        $details = [
            'greeting' => 'Hi ' . $User->name . ',',
            'body' => 'An account has been created for you as an Institute. Please login to your account with below instructions.',
            'email' => 'You login email address : ' . $request->email,
            'password' => 'Your password is : ' . $request->password,
            'thanks' => 'Thank you this is from ' . env('APP_NAME'),
            'actionText' => 'Login',
            'actionURL' => route('user.login'),
        ];

        Notification::send($User, new UserRegisterMailNotification($details));
    }

    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.institute.edit')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Institution';
        $data['card_title'] = 'Edit ' . $data['title'];
        $data['role'] = 'Institute';
        $data['row'] = User::find($id);
        $data['packages'] = Package::all();

        return view('admin.institute.edit', compact('data'));
    }

    public function passChange($id, Request $request)
    {

        if (is_null($this->user) || !$this->user->can('admin.institute.update.password')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $request->validate([
            'password' => 'required|confirmed|min:8|max:50',
        ]);


        DB::beginTransaction();
        try {

            $User = User::find($id);
            $User->password = bcrypt($request->password);
            $User->save();

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Institute password not updated !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.institute.edit', ['id' => $id, 'role' => $request->role]);
        }
        DB::commit();
        Toastr::success(trans('Institute Password Updated Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.institute.index');
    }

    public function destroy($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.institute.destroy')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $user = User::find($id);
        $result = $user->delete();

        // dependency condition
        if ($result) {
            $user->tickets()->delete();
            $user->bookReviews()->delete();
            $user->viewed()->delete();
            $user->borrowed()->delete();
            $user->favorites()->delete();
        }

        Toastr::success(trans('Institute Deleted Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

    public function assignBook(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('admin.institute.assignBook')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $validator = Validator::make($request->all(), [
            'books' => 'required',
        ]);

        if ($validator->fails()) {
            Toastr::error('Book field is required!', 'Error');
            return back();
        }
        try {

            $user = User::findOrFail($id);
            $books = $request->books;
            $dates = getBookValidity();
            $user->borrowed()->update(['is_valid' => 0]);
            $bookReader = new ReaderBookController();
            foreach ($books as $book) {
                $bookReader->handleBorrowing($book, $id, $dates, 1);
            }

            Toastr::success('Book Assigned successful!', 'Success');


            $title = 'An admin has been assigned some new books for this institution.';
            $this->saveUserNotification($title, 'user.book.borrowed', 'new_book', 'user', $user->id);
            $this->sendUserPushNotification([$user->id], 'Assigned book' ,$title, route('user.book.borrowed'));

        } catch (\Exception $e) {
            Log::alert($e->getMessage());
            Toastr::error('Something is wrong!', 'Error');
        }
        return back();
    }

    public function update($id, Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.institute.update')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email," . $id,
        ]);
        DB::beginTransaction();
        try {

            $User = User::find($id);
            $User->role_id = 3;
            $User->name = $request->name;
            $User->last_name = $request->last_name;
            $User->phone = $request->phone;
            $User->email = $request->email;
            $User->image = 'assets/images/default-user.png';
            $User->status = $request->status;
            $User->save();

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Institute not updated !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back()->withInput();
        }
        DB::commit();
        Toastr::success(trans('Institute Updated Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.institute.index');
    }
}
