<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\User;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;


    public function __construct()
    {
        $this->middleware('guest:user')->except('logout');
    }

    protected function guard()
    {
        return Auth::guard('user');
    }



    // protected $redirectTo;

    // protected function redirectTo() {

    //     if(auth()->user()->role_id == 1 ) {

    //         Toastr::success('Welcome to Admin Panel :-)','Success');
    //         return route('admin.dashboard');

    //     }elseif(auth()->user()->role_id == 2 ) {

    //         Toastr::success('Welcome to your profile :-)','Success');
    //         return route('user.dashboard');

    //     }else {
    //         $this->redirectTo = route('login');
    //     }

    // }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password', 'role_id');
    }
    protected function authenticated(Request $request, $user)
    {
        if (intval($user->status) === 0) {
            Auth::logout();
            Toastr::error('You are blocked by administration. Please contact with us', "Warning");
            return redirect()->route('user.login');
        }
    }

    public function modalLogin(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'success' => true,
                 'message' => 'Login successful'
                ]);
        }
        return response()->json(['success' => false, 'message' => 'Login failed. Please check your credentials']);
    }


    public function logout(Request $request)
    {

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }


}

