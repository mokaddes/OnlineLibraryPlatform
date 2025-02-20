<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\User\FlutterWaveController;
use App\Rules\UniquePhoneWithDialCode;
use Exception;
use Illuminate\Support\Facades\Log;
use Input;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\User;
use App\Models\Admin;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\BusinessCard;
use Illuminate\Http\Request;
use App\Models\BusinessField;
use App\Mail\SendEmailInvoice;
use App\Actions\User\UpdateUser;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Notifications\UserRegisterMailNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $user;

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // All Users
    public function index(Request $request)
    {

        if (is_null($this->user) || !$this->user->can('admin.user.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Users';
        if ($request->type == 'reader') {
            $users = User::withCount(['borrowed as borrowed_count' => function ($query) {
                $query->where('is_valid', 1);
            }])->where('role_id', '1');
        } elseif ($request->type == 'author') {
            $users = User::withCount(['products'])->where('role_id', '2');
        } else {
            $users = User::withCount(['products', 'borrowed as borrowed_count' => function ($query) {
                $query->where('is_valid', 1);
            }])->whereIn('role_id', ['1', '2']);
        }

        if (isset($request->country) && !empty($request->country)) {
            if ($request->country != 'All') {
                $users = User::where('country', $request->country);
            } else {
                $users = $users;
            }

        }
        $data['user_count'] = User::count();
        $data['reader_count'] = User::where('role_id', '1')->count();
        $data['author_count'] = User::where('role_id', '2')->count();

        $data['type'] = $request->type ?? 'all';
        $data['rows'] = $users->latest()->get();
        return view('admin.users.index', compact('data'));
    }

    public function adminIndex(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.admins.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Admins';
        $data['rows'] = Admin::latest()->get();
        return view('admin.admins.index', compact('data'));
    }

    public function view($id, Request $request)
    {

        if (is_null($this->user) || !$this->user->can('admin.user.view')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'User';
        $user = User::withCount(['products', 'borrowed as borrowed_count' => function ($query) {
            $query->where('is_valid', 1);
        }])->where('id', $id)->firstOrFail();
        $data['role'] = $request->role;
        $data['row'] = $user;
        return view('admin.users.view', compact('data', 'user'));
    }

    public function adminView($id, Request $request)
    {

        if (is_null($this->user) || !$this->user->can('admin.admins.view')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Admin';
        $user = Admin::where('id', $id)->firstOrFail();
        $data['row'] = $user;
        return view('admin.admins.view', compact('data'));
    }

    public function create(Request $request)
    {

        if (is_null($this->user) || !$this->user->can('admin.user.create')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        if (!$request->role && !in_array($request->role, ['Author', 'Institution'])) {
            abort(404);
        }

        $data['role'] = $request->role;
        $data['title'] = 'Author';
        $data['packages'] = Package::all();
        return view('admin.users.create', compact('data'));
    }

    public function adminCreate(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.admins.create')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Admin';
        $data['role'] = Role::all();
        return view('admin.admins.create', compact('data'));
    }

    public function store(Request $request)
    {

        if (is_null($this->user) || !$this->user->can('admin.user.store')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email",
            'password' => "required|confirmed|min:8|max:50",
            'phone' => ["required_unless:role,Institution", "unique:users,phone"],

        ], [
            'phone.required_unless' => 'Phone number is required',
        ]);

        DB::beginTransaction();

        try {

            if ($request->role == 'Author') {
                $role_id = '2';
            } elseif ($request->role == 'Institution') {
                $role_id = '3';
            } else {
                $role_id = '1';
            }

            if (isset($request->package) && !empty($request->package)) {
                $plan_id = $request->package;
            } else {
                $plan_id = 1;
            }

            $User = new User();
            $User->role_id = $role_id;
            $User->name = $request->name;
            $User->last_name = $request->last_name;
            $User->dial_code = '+' . $request->dial_code;
            $User->phone = $request->phone;
            $User->is_buy_book = $request->is_buy_book ?? 0;
            $User->country = $request->country;
            $User->country_code = $request->country_code;
            $User->email = $request->email;
            $User->password = bcrypt($request->password);
            $User->image = 'assets/images/default-user.png';
            $User->email_verified_at = now();
            $User->plan_id = $plan_id;
            $User->save();
            $user_plan = new FlutterWaveController();
            $package = Package::find($plan_id);
            $user_plan->saveUserPlan($User, $package, 100, '', '');

            $this->sendWelcomeEmail($request, $User);
            try {
                $setting = Setting::first();
                $email = $User->email;
                $role = $request->role ?? 'Author';
                subscribeToMailchimp($setting, $email, $User->name, $User->last_name, $role);
            } catch (Exception $e) {
                Log::alert($e->getMessage());
            }

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('User not Created !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.user.create', ['role' => $request->role]);
        }
        DB::commit();
        Toastr::success('User Created Successfully', 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.user.index');
    }

    public function sendWelcomeEmail($request, $User)
    {
        if (is_numeric($request->role)) {
            $role = "Admin";
        } else {
            $role = $request->role;
        }
        $details = [
            'greeting' => 'Hi ' . $User->name . ',',
            'body' => 'An account has been created for you as an ' . $role . '. Please login to your account with below instructions.',
            'email' => 'You login email address : ' . $request->email,
            'password' => 'Your password is : ' . $request->password,
            'thanks' => 'Thank you this is from ' . env('APP_NAME'),
            'actionText' => 'Login',
            'actionURL' => route('user.login'),
        ];

        Notification::send($User, new UserRegisterMailNotification($details));
    }

    public function adminStore(Request $request)
    {
        // dd($request->all());
        if (is_null($this->user) || !$this->user->can('admin.admins.store')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:admins,email",
            'password' => "required|min:8|max:50",
        ]);

        DB::beginTransaction();

        try {

            $User = new Admin();
            $User->role_id = $request->role;
            $User->name = $request->name;
            $User->email = $request->email;
            $User->password = bcrypt($request->password);
            $User->email_verified_at = now();
            $User->save();
            $this->sendWelcomeEmail($request, $User);

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Admin not Created !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        DB::commit();
        Toastr::success('Admin Created Successfully', 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.admins.index');
    }

    public function edit($id, Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.user.edit')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $role = $request->role;
        $data['title'] = ($role == 'Author') ? 'Author' : (($role == 'Institution') ? 'Institution' : 'Reader');
        $data['card_title'] = 'Edit ' . $data['title'];
        $data['role'] = $request->role;
        $data['row'] = User::find($id);
        $data['packages'] = Package::all();

        return view('admin.users.edit', compact('data'));
    }

    public function adminEdit($id, Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.admins.edit')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Admin';
        $data['row'] = Admin::find($id);
        $data['role'] = Role::all();

        return view('admin.admins.edit', compact('data'));
    }

    public function adminUpdate($id, Request $request)
    {

        if (is_null($this->user) || !$this->user->can('admin.admins.update')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:admins,email," . $id,
            // 'password'  => 'required',
        ]);


        DB::beginTransaction();
        try {

            $User = Admin::find($id);
            $User->name = $request->name;
            $User->email = $request->email;
            $User->role_id = $request->role;
            $User->save();

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Admin not updated !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        DB::commit();
        Toastr::success(trans('Admin Updated Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.admins.index');
    }

    public function passChange($id, Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.user.update.password')) {
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
            Toastr::error(trans('User password not updated !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.user.edit', ['id' => $id, 'role' => $request->role]);
        }
        DB::commit();
        Toastr::success(trans('User Password Updated Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.user.index');
    }

    // View User
    // public function viewUser(Request $request, $id)
    // {
    //     $user_details = Admin::where('id', $id)->first();
    //     if ($user_details == null) {
    //         return view('errors.404');
    //     } else {
    //         $user_cards = BusinessCard::where('user_id',$id)->where('status','!=',2)->orderBy('card_url','asc')->get();
    //         $settings = Setting::where('status', 1)->first();
    //         return view('admin.users.view-user', compact('user_details', 'user_cards', 'settings'));
    //     }
    // }

    // Edit User
    public function editUser(Request $request, $id)
    {
        $user_details = Admin::where('id', $id)->first();
        $settings = Setting::where('status', 1)->first();
        if ($user_details == null) {
            return view('errors.404');
        } else {
            return view('admin.users.edit-user', compact('user_details', 'settings'));
        }
    }


    // Update User
    public function updateUser(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'user_id' => 'required',
                'full_name' => 'required',
                'email' => 'required'
            ]);
            $user = Admin::where('id', $request->user_id)->first();
            $user->email = $request->email;
            $user->name = $request->full_name;
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }
            $user->plan_validity = $request->plan_validity;
            if (isset($request->no_of_vcards)) {
                $plan_details = json_decode($user->plan_details);
                $plan_array = [];
                foreach ($plan_details as $key => $value) {
                    if ($key == 'no_of_vcards') {
                        $plan_array[$key] = $request->no_of_vcards;
                    } else {
                        $plan_array[$key] = $value;
                    }
                }
                $user->plan_details = json_encode($plan_array);
            }
            $user->billing_address = $request->billing_address;
            $user->billing_city = $request->billing_city;
            $user->billing_state = $request->billing_state;
            $user->billing_zipcode = $request->billing_zipcode;
            $user->billing_country = $request->billing_country;
            $user->phone = $request->phone;
            $user->designation = $request->designation;
            $user->company_name = $request->company_name;
            $user->company_websitelink = $request->company_websitelink;
            $user->status = $request->status;
            $user->user_type = $request->user_type;
            $user->update();

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('User not updated!'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.users');
        }
        DB::commit();
        Toastr::success(trans('User updated Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.users');
    }


    // Update status

    public function update($id, Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.user.update')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $role_id = 1;
        $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email,{$id}",
            'phone' => ["required_unless:role,Institution", "unique:users,phone,{$id}", new UniquePhoneWithDialCode($id, $request->input('dial_code'))],

        ], [
            'phone.required_unless' => 'Phone number is required',
        ]);

        if ($request->role == 'Author') {
            $role_id = 2;
        } elseif ($request->role == 'Institution') {
            $role_id = 3;
        } else {
            $role_id = 1;
        }

        DB::beginTransaction();
        try {

            if (isset($request->package) && !empty($request->package)) {
                $plan_id = $request->package;
            } else {
                $plan_id = 1;
            }

            $User = User::find($id);
            $User->role_id = $role_id;
            $User->name = $request->name;
            $User->last_name = $request->last_name;
            $User->dial_code = '+' . $request->dial_code;
            $User->phone = $request->phone;
            $User->is_buy_book = $request->is_buy_book ?? 0;
            $User->country = $request->country;
            $User->country_code = $request->country_code;
            $User->email = $request->email;
            $User->gender = $request->gender;
            $User->age = $request->age;
            $User->image = 'assets/images/default-user.png';
            $User->email_verified_at = now();
            $User->status = $request->status;
            $User->plan_id = $plan_id;
            $User->save();

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('User not updated !', 'Error');
            return redirect()->route('admin.user.edit', ['id' => $id, 'role' => $request->role]);
        }
        DB::commit();
        Toastr::success('User Updated Successfully!', 'Success');
        return redirect()->route('admin.user.index');
    }


    // Login As User

    public function updateStatus(Request $request)
    {
        $user_details = Admin::where('id', $request->query('id'))->first();
        if ($user_details->status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }
        Admin::where('id', $request->query('id'))->update(['status' => $status]);
        Toastr::success(trans('User Status Updated Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.users');
    }

    public function authAs(Request $request, $id)
    {
        $user_details = Admin::where('id', $id)->where('status', 1)->first();
        if (isset($user_details)) {
            Auth::loginUsingId($user_details->id);
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('admin.users')->with('info', 'User account was not found!');
        }
    }

    public function destroy($id)
    {

        if (is_null($this->user) || !$this->user->can('admin.user.destroy')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        try {
            DB::beginTransaction();
            $user = User::find($id);// dependency condition
            if ($user) {
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
            }
            Toastr::success(trans('User Deleted Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        } catch (Exception $e) {
            DB::rollBack();
            Toastr::error(trans('Something went wrong!'), 'Error', ["positionClass" => "toast-top-right"]);
        }
        DB::commit();
        return redirect()->back();
    }

    public function adminDestroy($id)
    {

        if (is_null($this->user) || !$this->user->can('admin.admins.destroy')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        Admin::find($id)->delete();
        Toastr::success(trans('Admin Deleted Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }


}
