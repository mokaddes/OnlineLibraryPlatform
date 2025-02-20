<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\FlutterWaveController;
use App\Mail\WelcomeMail;
use App\Models\BorrowedBook;
use App\Models\Notification as WebNotification;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductFavourite;
use App\Models\ProductView;
use App\Models\PromocodeBook;
use App\Models\PromocodeBookUsed;
use App\Models\PromocodePackage;
use App\Models\PromocodePackageUsed;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPlan;
use App\Notifications\UserRegisterMailNotification;
use App\Rules\UniquePhoneWithDialCode;
use App\Traits\RepoResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use DateTime;
use DateTimeZone;

class AuthController extends Controller
{
    use RepoResponse;



    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'dial_code' => ['required'],
            'phone' => ['required','unique:users,phone', new UniquePhoneWithDialCode(null, $request->input('dial_code'))],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'country' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $uniquePhone = User::where('phone', $request->phone)
            ->where('dial_code', $request->dialCode)
            ->exists();
        if ($uniquePhone) {
            return $this->apiResponse(0, 422, 'Phone number is already taken.', '',);
        }

        $data = $request->all();
        $userData = [
            'name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'dial_code' => $data['dial_code'],
            'phone' => $data['phone'],
            'country' => $data['country'] ?? '',
            'country_code' => $data['country_code'] ?? '',
            'role_id' => 1,
            'device_id' => $data['device_id'] ?? '',
            'password' => bcrypt($data['password']),
        ];

        $setting = Setting::first();
        if ($setting->email_verification == 0) {
            $userData['email_verified_at'] = now();
        }
        $user = User::create($userData);
        $this->saveUsedPackage($user);
        auth()->login($user);
        $details = [
            'name' => $user->name,
            'header' => ' Welcome to ' . config('app.name'),
            'body' => 'We are excited to have you as a reader. Your account has been successfully created. Please log in to explore and enjoy the features of our platform.',
            'actionText' => 'Login',
            'actionURL' => route('user.login'),
        ];

        Mail::to($user->email)->send(new WelcomeMail($details));

        $data = $this->getUserResponse($user);

        return $this->apiResponse(1, 200, 'User registered successfully.', '', $data);
    }

    /**
     * @param User $user
     * @return void
     */
    public function saveUsedPackage(User $user): void
    {
        try {
            $user_plan = new FlutterWaveController();
            $package = Package::where('price', 0)->first();
            $user_plan->saveUserPlan($user, $package, 0, '', '');
            $setting = Setting::first();
            subscribeToMailchimp($setting, $user->email, $user->name, $user->last_name, 'Reader');
        } catch (Exception $e) {
            Log::alert($e->getMessage());
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required'],
            'password' => ['required'],
            'role_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $credentials = $request->only('email', 'password', 'role_id');

        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->apiResponse(0, 401, 'No user found..', 'Unauthorized');
        }
        $user = JWTAuth::user();
        if (!empty($request->device_id)) {
            $user->device_id = $request->device_id;
            $user->save();
        }

        $data = $this->getUserResponse($user);

        return $this->apiResponse(1, 200, 'Data successfully found.', '', $data);
    }

    /**
     * @param User $user
     * @return array
     */
    public function getUserResponse(User $user): array
    {
        $token = JWTAuth::fromUser($user);
        $user->role_id = strval($user->role_id);
        $user->plan_id = strval($user->plan_id);
        $user->plan = $user->currentUserPlan->package ?? 0;
        $user->is_buy_book = (int) $user->is_buy_book;

        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user_role' => $user->role_id,
            'user' => $user,
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];
        return $data;
    }

    public function profile()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user->plan = $user->currentUserPlan->package ?? 0;
        $user->is_buy_book = (int) $user->is_buy_book;
        $data = [
            'user' => $user,
        ];
        return $this->apiResponse(1, 200, 'Data successfully found.', '', $data);
    }

    public function dashboard()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $borrowed_books = BorrowedBook::whereHas('book', function ($q) {
            $q->with('author', 'category')
                ->where('status', '10');
        })->where('user_id', $user->id)->with('book')->latest()->get();
        $this->getMapBook($borrowed_books, $user->id);
        $viewed_books = ProductView::whereHas('book', function ($q) {
            $q->with('author', 'category')
                ->where('status', '10');
        })->where('user_id', $user->id)->with('book')->latest()->get();
        $this->getMapBook($viewed_books, $user->id);
        $my_books = Product::where('user_id', $user->id)->latest()->get();
        $decline_books = Product::where('user_id', $user->id)->latest()->where('status', 30)->get();
        $pending_books = Product::where('user_id', $user->id)->latest()->where('status', 0)->get();
        $fav_books = ProductFavourite::where('user_id', $user->id)->count();
        $readers = $user->productViews()->with('user', 'book')->latest('total_view')->get();
        $remainingDays = 0;
        if (isset($user->currentUserPlan->expired_date)) {
            $currentPlanExpireDate = Carbon::parse($user->currentUserPlan->expired_date);
            $remainingDays = now()->diffInDays($currentPlanExpireDate);
        }
        $notifications = WebNotification::where('user_id', $user->id)->where('is_read', 0)->latest()->count();

        $role_id = $user->role_id;
        if ($role_id == 1) {
            $data = [
                'last_viewed_books' => $viewed_books,
                'last_borrowed_books' => $borrowed_books->take(10),
                'total_viewed_books' => $user->viewed()->count(),
                'total_borrowed_books' => $user->borrowed()->count(),
                'subscription_remaining' => $remainingDays,
                'user' => $user,
                'plan' => $user->currentUserPlan->package ?? '',
            ];
        } elseif ($role_id == 2) {
            $data = [
                'readers_count' => $readers->count(),
                'top_readers' => $readers,
                'my_books' => $my_books,
                'my_books_count' => $my_books->count(),
                'pending_books' => $pending_books,
                'decline_books' => $decline_books,
                'pending_books_count' => $pending_books->count(),
                'decline_books_count' => $decline_books->count(),
                'user' => $user,
            ];
        } else {
            $data = [
                'last_viewed_books' => $viewed_books,
                'last_issued_books' => $borrowed_books->take(10),
                'total_viewed_books' => $user->viewed()->count(),
                'total_issued_books' => $user->borrowed()->count(),
                'total_fav_books' => $fav_books,
                'user' => $user,
            ];
        }
        $data['unreadNotificationCount'] = $notifications ?? 0;
        $setting = Setting::select('tawk_chat_url')->first();
        $data['tawk_chat_url'] = $setting->tawk_chat_url;

        return $this->apiResponse(1, 200, 'Data successfully found.', '', $data);

    }

    public function getMapBook($viewed_books, $user_id)
    {
        $viewed_books->each(function ($book) use ($user_id) {
            $bookData = $book->book;
            $borrowed = $book->book->borrowedBooks()->where('user_id', $user_id)->first();
            $bookData->is_valid = $borrowed->is_valid ?? '';
            $bookData->borrowed_startdate = $borrowed->borrowed_startdate ?? '';
            $bookData->borrowed_enddate = $borrowed->borrowed_enddate ?? '';
            $bookData->borrowed_nextdate = $borrowed->borrowed_nextdate ?? '';
            $bookData->is_institution = $borrowed->is_institution ?? '';
            $bookData->is_favorite = is_favorite($bookData->id ?? 0);
            $bookData->is_borrowed = is_borrowed($bookData->id ?? 0);

            return $bookData;
        });
    }

    public function transactions()
    {
        $user = JWTAuth::user();
        $transactions = Transaction::with('book:id,code,title,slug', 'package:id,title,price')->where('user_id', $user->id)->latest()->get();
        if ($transactions && $transactions->count() > 0) {
            return $this->apiResponse(1, 200, 'Transaction are successfully found.', '', $transactions);
        } else {
            return $this->apiResponse(0, 404, 'No transaction found', '', []);
        }

    }

    public function profileUpdate(Request $request)
    {
        $user = JWTAuth::user();
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id,],
            'phone' => ['nullable', new UniquePhoneWithDialCode($user->id, $request->input('dial_code'))],
            'dial_code' => ['nullable'],
            'country' => ['nullable'],
            'image' => 'nullable|file',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);

        }

        $uniquePhone = User::where('phone', $request->phone)
            ->where('dial_code', $request->dialCode)->where('id', '!=', $user->id)
            ->exists();
        if ($uniquePhone && !empty($request->phone)) {
            return $this->apiResponse(0, 422, 'Phone number is already taken.', '',);
        }
        if ($request->has('image')) {
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
        $user->name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->dial_code = $request->dial_code;
        $user->phone = $request->phone;
        $user->country = $request->country;
        $user->country_code = $request->country_code;
        $user->address = $request->address;
        $user->save();
        $data = ['user' => $user];
        return $this->apiResponse(1, 200, 'Profile successfully updated.', '', $data);
    }

    public function passwordUpdate(Request $request)
    {
        $user = JWTAuth::user();
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!password_verify($request->current_password, $user->password)) {
            return response()->json(['errors' => ['current_password' => 'Current password is incorrect']], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password updated successfully', 'user' => $user]);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'exists:users,email'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['status' => 0, 'error' => 'We can\'t find a user with that email address']);
        }
        $password = Str::random(8);
        $user->password = Hash::make($password);
        $user->save();

        $details = [
            'subject' => 'Reset password  for ' . env('APP_NAME'),
            'greeting' => 'Hi ' . $user->name . ',',
            'body' => 'Your password is reset according to a password reset request for your account. Please login and change your password.',
            'email' => 'You login email address : ' . $request->email,
            'password' => 'Your temporary password is : ' . $password,
            'actionText' => 'Login',
            'actionURL' => route('user.login'),
        ];
        $this->sendWelcomeEmail($details, $user);
        $data = [
            'password' => $password, 'user' => $user
        ];
        return $this->apiResponse(1, 200, 'Password has been reset successfully.', '', $data);

    }

    public function sendWelcomeEmail($details, $user)
    {
        Notification::send($user, new UserRegisterMailNotification($details));
    }

    public function delete()
    {
        DB::beginTransaction();
        try {
            $user = JWTAuth::user();
            $user->products()->delete();
            $user->favorites()->delete();
            $user->borrowed()->delete();
            $user->viewed()->delete();
            $user->clubs()->delete();
            $user->email = 'deleted_' . $user->id . '_' . $user->email;
            $user->phone = 'deleted_' . $user->id . '_' . $user->phone;
            $user->status = 0;
            $user->password = Hash::make('deleted_user');
            $user->save();
            auth('api')->logout();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse(0, 422, 'Something is wrong.', $e->getMessage(), $errorArray);
        }

        return $this->apiResponse(1, 200, 'Account deleted successfully');


    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'User logged out successfully']);
    }

    public function promoApply(Request $request)
    {

        if ($request->apply_for == 'book') {
            $code = 'promocode_books,code';
        } else {
            $code = 'promocode_package,code';
        }

        $validator = Validator::make($request->all(), [
            'apply_for' => 'required|in:book,package',
            'code' => 'required|exists:' . $code
        ], [
            'code.exists' => 'You are using an invalid code.'
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(0, 422, $validator->errors()->first(), $validator->errors()->first(), $validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $userId = $user->id;
            $dates = getBookValidity();
            if ($request->apply_for == 'book') {
                $promoBook = PromocodeBook::where('code', $request->code)->firstOrFail();
                $isAvailable = $promoBook->used_count < $promoBook->user_limit;
                $isValid = $promoBook->status == 1 && $promoBook->valid_date > now();
                $isApplied = PromocodeBookUsed::where('promocode_book_id', $promoBook->id)->where('user_id', $userId)->exists();
                $bookIds = $promoBook->book_ids;
                if ($isAvailable && $isValid && !$isApplied && !empty($bookIds)) {

                    $used = new PromocodeBookUsed();
                    $used->promocode_book_id = $promoBook->id;
                    $used->user_id = $userId;
                    $used->save();

                    foreach ($bookIds as $bookId) {
                        $user->borrowed()->where('product_id', $bookId)->update(['is_valid' => 0]);
                        $borrowed = new BorrowedBook();
                        $borrowed->product_id = $bookId;
                        $borrowed->user_id = $userId;
                        $borrowed->is_valid = 1;
                        $borrowed->promocode_book_id = $promoBook->id;
                        $borrowed->borrowed_startdate = Carbon::now();
                        $borrowed->borrowed_enddate = $promoBook->valid_date;
                        $borrowed->borrowed_nextdate = Carbon::parse($promoBook->valid_date)->addDays(2);
                        $borrowed->save();
                    }
                    $promoBook->used_count = $promoBook->used()->count();;
                    $promoBook->save();
                } else {
                    return $this->apiResponse(0, 422, 'PromoCode is not valid.');
                }
            } else {
                $promoPackage = PromocodePackage::where('code', $request->code)->firstOrFail();
                $isAvailable = $promoPackage->used_count < $promoPackage->user_limit;
                $isValid = $promoPackage->status == 1 && $promoPackage->valid_date > now();
                $isApplied = PromocodePackageUsed::where('promocode_package_id', $promoPackage->id)->where('user_id', $userId)->exists();
                if ($isAvailable && $isValid && !$isApplied && !empty($promoPackage->package_id)) {
                    $package = Package::find($promoPackage->package_id);
                    $used = new PromocodePackageUsed();
                    $used->promocode_package_id = $promoPackage->id;
                    $used->user_id = $userId;
                    $used->save();

                    $user->plan()->update(['status' => 0]);

                    $user_plan = new UserPlan();
                    $user_plan->user_id = $user->id;
                    $user_plan->package_id = $package->id;
                    $user_plan->plan_id = 0;
                    $user_plan->package_promocode_id = $promoPackage->id;
                    $user_plan->expired_date = $promoPackage->valid_date;
                    $user_plan->status = 1;
                    $user_plan->save();
                    $user->plan_id = $package->id;
                    $user->save();
                } else {
                    return $this->apiResponse(0, 422, 'PromoCode is not valid.');
                }

                $promoPackage->used_count = $promoPackage->used()->count();
                $promoPackage->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse(0, 422, 'Something went wrong.',  $e->getMessage(), $errorArray);
        }
        return $this->apiResponse(1, 200, 'PromoCode Successfully Applied.');
    }

    public function billing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billing_name' => "required",
            'billing_email' => "required|email",
            'billing_phone' => "required",
            'billing_country' => "nullable",
            'billing_address' => "required",
            'billing_city' => "required",
            'billing_state' => "required",
            'billing_zipcode' => "required",
            'type' => "required|in:Personal,Business",
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(0, 422, 'Validation Error.', $validator->errors()->first(), '', $validator->errors()->toArray());
        }

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $user->billing_name = $request->billing_name;
            $user->billing_email = $request->billing_email;
            $user->billing_phone = $request->billing_phone;
            $user->billing_dial_code = $request->billing_dial_code;
            $user->billing_country_code = $request->billing_country_code;
            $user->country = $request->billing_country;
            $user->address = $request->billing_address;
            $user->city = $request->billing_city;
            $user->state = $request->billing_state;
            $user->zipcode = $request->billing_zipcode;
            $user->type = $request->type;
            $user->save();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->apiResponse(0, 422, 'Something went wrong.');
        }
        DB::commit();
        return $this->apiResponse(1, 200, 'Billing information successfully updated.', '', $user);
    }

    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'provider' => ['required'],
            'access_token' => ['required'],
            'phone' => ['nullable', new UniquePhoneWithDialCode(null, $request->input('dial_code'))],
        ]);

        if ($validator->fails()) {
            return $this->apiResponse('0', 422, $validator->errors()->first(), [], '', $validator->errors());
        }
        $provider = $request->input('provider');
        $token = $request->access_token;
        try {
            Socialite::driver($provider)->userFromToken($token);
        } catch (Exception $e) {
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse(0, 404, 'Data not found.', '', '', $errorArray);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $fullName = $request->name;
            $nameParts = explode(' ', $fullName, 2);
            $firstName = $nameParts[0] ?? $request->id;
            $lastName = $nameParts[1] ?? null;
            try {
                $user = new User();
                $user->name = $firstName;
                $user->last_name = $lastName;
                $user->email = $request->email;
                $user->password = bcrypt('12345678');
                $user->email_verified_at = now();
                $user->role_id = 1;
                $user->provider = $provider;
                $user->provider_id = $request->id ?? null;
                $user->device_id = $request->device_id;
                $user->phone = $request->phone;
                $user->dial_code = $request->dial_code;
                $user->country_code = $request->country_code;
                $imageUrl = $request->imageUrl;
                if ($imageUrl) {
                    $image = $this->saveImageFormUrl($imageUrl);
                    $user->image = $image;
                }
                $user->save();
                $this->saveUsedPackage($user);
            } catch (Exception $e) {
                $errorArray = [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ];
                return $this->apiResponse(0, 500, 'Something is wrong.', '', '', $errorArray);
            }
        }
        Auth::guard('api')->login($user);

        $data = $this->getUserResponse($user);

        return $this->apiResponse(1, 200, 'Data successfully found.', '', $data);

    }

    /**
     * @param $imageUrl
     * @return string
     */
    public function saveImageFormUrl($imageUrl): string
    {
        try {
            $imageContents = file_get_contents($imageUrl);
            $directory = 'uploads/user_profile';
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0777, true);
            }
            $fileExtension = pathinfo($imageUrl, PATHINFO_EXTENSION);
            $fileName = Str::uuid() . '.' . $fileExtension;
            file_put_contents(public_path($directory . '/' . $fileName), $imageContents);
            return $directory . '/' . $fileName;
        } catch (Exception $e) {
            return '';
        }
    }

    public function profileComplete(Request $request)
    {

        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'phone' => ["required", "unique:users,phone,{$user->id}", new UniquePhoneWithDialCode($user->id, $request->input('dial_code'))],
        ]);

        return $this->apiResponse('0', 400, $validator->errors()->first(), [], $validator->errors());


        $user->dial_code = '+' . $request->dial_code;
        $user->phone = $request->phone;
        $user->country = $request->country;
        $user->country_code = $request->country_code;
        $user->save();
        return $this->apiResponse('1', 200, 'Profile completed successfully');


    }

}

