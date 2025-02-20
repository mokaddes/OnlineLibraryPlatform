<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BorrowedBook;
use App\Models\Package;
use App\Models\PromocodeBook;
use App\Models\PromocodeBookUsed;
use App\Models\PromocodePackage;
use App\Models\PromocodePackageUsed;
use App\Models\UserPlan;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PromoCodeController extends Controller
{
    public function index()
    {
        return view('user.promo-code.index');
    }

    public function store(Request $request)
    {

        if ($request->apply_for == 'book') {
            $code = 'promocode_books,code';
        }else{
            $code = 'promocode_package,code';
        }

        $request->validate([
            'apply_for' => 'required|in:book,package',
            'code' => 'required|exists:'.$code
        ]);

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
                    $promoBook->used_count = $promoBook->used()->count();
                    $promoBook->save();
                }else{
                    Toastr::error('Code is not valid or already applied.', 'Error');
                    return back();
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
                }else{
                    Toastr::error('Code is not valid or already applied.', 'Error');
                    return back();
                }

                $promoPackage->used_count = $promoPackage->used()->count();
                $promoPackage->save();
            }
            DB::commit();
            Toastr::success('PromoCode Successfully Applied.', 'Success');
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Something went wrong.', 'Error');
        }
        return redirect()->route('user.dashboard');
    }
}
