<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\User\FlutterWaveController;
use App\Mail\WelcomeMail;
use App\Models\Package;
use App\Models\User;
use App\Models\UserPlan;
use App\Models\Setting;
use App\Rules\UniquePhoneWithDialCode;
use DrewM\MailChimp\MailChimp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Notifications\UserRegisterMailNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    // use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'unique:users,phone', new UniquePhoneWithDialCode(null, $data['dial_code'] ?? '')],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function create(array $data)
    {

        $userData = [
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'dial_code' => '+' . $data['dial_code'],
            'phone' => $data['phone'],
            'country' => $data['country'],
            'country_code' => $data['country_code'],
            'password' => bcrypt($data['password']),
        ];

        $setting = Setting::first();
        if ($setting->email_verification == 0) {
            $userData['email_verified_at'] = now();
        }

        $user = User::create($userData);
        $email = $user->email;

        try {
            subscribeToMailchimp($setting, $email, $user->name, $user->last_name, 'Reader');
        } catch (Exception $e) {
            Log::alert($e->getMessage());
        }
        $package = Package::where('price', 0)->first();
        $user_plan = new FlutterWaveController();
        $user_plan->saveUserPlan($user, $package, 0, '', '');

        $this->sendWelcomeEmail($user, $data['password']);


        return $user;
    }

    public function sendWelcomeEmail($user, $password)
    {

        $details = [
            'name' => $user->name,
            'header' =>' Welcome to '.config('app.name'),
            'body' => 'We are excited to have you as a reader. Your account has been successfully created. Please log in to explore and enjoy the features of our platform.',
            'actionText' => 'Login',
            'actionURL' => route('user.login'),
        ];

        Mail::to($user->email)->send(new WelcomeMail($details));
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));
        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/user';
    }

    protected function registered(Request $request, $user)
    {
        Auth::logout();
        Toastr::success(
            trans('Successfully registered and logged in ' . config('app.name')),
            'Success',
            ["positionClass" => "toast-top-right"]
        );
        $this->guard()->login($user);
    }

    protected function guard()
    {
        return Auth::guard();
    }


}
