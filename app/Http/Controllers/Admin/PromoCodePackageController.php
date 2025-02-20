<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\FlutterWaveController;
use App\Models\Package;
use App\Models\PromocodePackage;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PromoCodePackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $promoCode = PromocodePackage::latest()->get();
        $data = [
            'title' => 'Package Promocode',
            'promoCode' => $promoCode
        ];
        return view('admin.package-promo.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|Application|View
     */
    public function create()
    {
        $packages = Package::where('status', '1')->get();
        $data = [
            'title' => 'Create PromoCode for package',
            'packages' => $packages,
        ];
        return view('admin.package-promo.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:8|unique:promocode_package,code',
            'validity' => 'required|integer',
            'user_limit' => 'required|integer',
            'package_id' => 'required|exists:packages,id',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            Toastr::error($validator->errors()->first(), 'Error');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $promo = new PromocodePackage();
            $this->savePackagePromo($request, $promo);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Something went wrong.', 'Error');
            return redirect()->back()->withInput();
        }

        return redirect()->route('admin.package-promo.index');
    }

    /**
     * @param Request $request
     * @param PromocodePackage $promo
     * @return void
     */
    public function savePackagePromo(Request $request, PromocodePackage $promo): void
    {
        $promo->title = $request->title;
        $promo->code = $request->code;
        $promo->validity = $request->validity;
        $promo->valid_date = Carbon::now()->addDays($request->validity);
        $promo->user_limit = $request->user_limit;
        $promo->package_id = $request->package_id;
        $promo->status = $request->status;
        $promo->save();
        Toastr::success('Promo code package saved successfully', 'Success');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        $promocodePackage = PromocodePackage::findOrFail($id);
        $packages = Package::where('status', '1')->get();
        $data = [
            'title' => 'Edit PromoCode package',
            'packages' => $packages,
            'promocodePackage' => $promocodePackage
        ];
        return view('admin.package-promo.create', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:8|unique:promocode_package,code,' . $id,
            'validity' => 'required|integer',
            'user_limit' => 'required|integer',
            'package_id' => 'required|exists:packages,id',
            'status' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            Toastr::error($validator->errors()->first(), 'Error');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            $promo = PromocodePackage::findOrFail($id);
            $this->savePackagePromo($request, $promo);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Something went wrong.', 'Error');
        }

        return redirect()->route('admin.package-promo.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $promoCodePackage = PromocodePackage::findOrFail($id);
            if ($promoCodePackage->planUser()->count() > 0) {
                $package = Package::where('price', 0)->first();
                $user_plan = new FlutterWaveController();
                foreach ($promoCodePackage->planUser as $user) {
                    $user_plan->saveUserPlan($user, $package, 0, '', '');
                }
            }
            $promoCodePackage->delete();
            Toastr::success('Promo code package deleted successfully', 'Success');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Something went wrong.', 'Error');
        }
        return redirect()->back();
    }
}
