<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Api\AuthController;
use App\Models\Setting;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class FacebookLoginController extends Controller
{


    public function __construct()
    {
        $setting = Setting::select('facebook_client_secret', 'facebook_client_id')->first();
        Config::set('services.facebook.client_id', $setting->facebook_client_id);
        Config::set('services.facebook.client_secret', $setting->facebook_client_secret);
        Config::set('services.facebook.graph_api_version', 'v3.3');
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }


    public function handleFacebookCallback()
    {
        try {
            try {
                $user = Socialite::driver('facebook')->user();
            } catch (\Exception $e) {
                \Log::error($e);
                return redirect()->route('users.login')->with('error', 'User not found');
            }

            $finduser = User::where('email', $user->email)->first();
            if ($finduser) {
                Auth::logout();
                Auth::login($finduser);
                return redirect('/user');
            } else {
                $fullName = $user->name;
                $nameParts = explode(' ', $fullName, 2);
                $firstName = $nameParts[0] ?? $user->id;
                $lastName = $nameParts[1] ?? null;
                $newUser = new User();
                $newUser->name = $firstName;
                $newUser->last_name =$lastName;
                $newUser->email =  $user->email;
                $newUser->password = bcrypt('12345678');
                $newUser->email_verified_at = now();
                $newUser->role_id = 1;
                $newUser->provider = 'facebook';
                $newUser->provider_id = $user->id ?? null;
                $newUser->save();
                $package = new AuthController();
                $package->saveUsedPackage($newUser);
                Auth::logout(); // Log out the current user, if any
                Auth::login($newUser);
            }
            Toastr::success(
                trans('Successfully logged in ' . config('app.name')),
                'Success',
                ["positionClass" => "toast-top-right"]
            );

            return redirect('/user');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->route('user.login')->with('error', 'An error occurred');
        }
    }

}
