<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Rules\UniquePhoneWithDialCode;
use App\Traits\RepoResponse;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileCompleteController extends Controller
{
    use RepoResponse;

    public function index()
    {
        $data['title'] = 'Profile Complete';
        $data['og_title'] = '';
        $data['og_description'] = '';
        $data['og_image'] = '';
        $data['contact'] = '';
        return view('frontend.profileComplete', $data);
    }

    public function profileComplete(Request $request)
    {

        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'phone' => ["required", "unique:users,phone,{$user->id}", new UniquePhoneWithDialCode($user->id, $request->input('dial_code'))],
        ]);
        if ($validator->fails()) {
            Toastr::error($validator->errors()->first(), 'Error');
            return back()->withInput();
        }

        $user->dial_code = '+' . $request->dial_code;
        $user->phone = $request->phone;
        $user->country = $request->country;
        $user->country_code = $request->country_code;
        $user->save();

        Toastr::success('Profile successfully completed.', 'Success');
        return redirect()->route('user.dashboard');

    }
}
