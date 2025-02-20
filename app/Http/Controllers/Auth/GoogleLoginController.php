<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Api\AuthController;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    /**
    * Redirect the user to the GitHub authentication page.
    *
    * @return \Illuminate\Http\Response
    */
   public function redirectToProvider()
   {
       return Socialite::driver('google')->redirect();
   }

   /**
    * Obtain the user information from GitHub.
    *
    * @return Application|Redirector|RedirectResponse
    */
   public function handleProviderCallback()
   {
        try{
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->email)->first();
            if ($user) {
                Auth::login($user);
                Toastr::success(
                    trans('Successfully logged in ' . config('app.name')),
                    'Success',
                    ["positionClass" => "toast-top-right"]
                );
                return redirect('/user');
            } else {
                $newUser = new User();
                $newUser->name = $googleUser->user['given_name'];
                $newUser->last_name = $googleUser->user['family_name'] ?? '';
                $newUser->email =  $googleUser->email;
                $newUser->password = bcrypt('12345678');
                $newUser->email_verified_at = now();
                $newUser->role_id = 1;
                $newUser->provider = 'google';
                $newUser->provider_id = $googleUser->id ?? null;
                $newUser->save();
                $package = new AuthController();
                $package->saveUsedPackage($newUser);

                Auth::login($newUser);
                Toastr::success(
                    trans('Successfully logged in ' . config('app.name')),
                    'Success',
                    ["positionClass" => "toast-top-right"]
                );
                return redirect('/user');
            }
        } catch (Exception $e) {
            Toastr::error($e->getMessage(), 'Error', ["positionClass" => "toast-top-right"]);
        }
    }

}
