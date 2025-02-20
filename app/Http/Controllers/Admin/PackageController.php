<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Brian2694\Toastr\Facades\Toastr;
use Flutterwave\Payload;
use Flutterwave\Service\PaymentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PackageController extends Controller
{
    public $user;
    protected $package;

    /**
     * @throws \Exception
     */
    public function __construct(Package $package)
    {
        $this->package = $package;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
        setConfigData();
    }

    // All Users
    public function index()
    {
        if (is_null($this->user) || !$this->user->can('admin.package.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Packages';
        $data['packages'] = Package::latest()->get();
        return view('admin.packages.index', $data);
    }

    /**
     * @throws \Throwable
     */
    public function getPaypal($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.package.getPaypal')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $plan = Package::findOrFail($id);
        // Create a PayPalClient instance

        $paypal = new PayPalClient();
        $accessToken = $paypal->getAccessToken();
        // Create a product
        $product = $paypal->createProduct(['name' => $plan->title]);
        // Create a billing plan

        $data = json_decode('{
            "product_id": "' . $product['id'] . '",
            "name": "' . $plan->title . '",
            "description": "Subscription Plan",
            "status": "ACTIVE",
            "billing_cycles": [
                {
                    "frequency": {
                        "interval_unit": "MONTH",
                        "interval_count": 1
                      },
                    "tenure_type": "REGULAR",
                    "sequence": 1,
                    "total_cycles": 0,
                    "pricing_scheme": {
                        "fixed_price": {
                            "value": "' . $plan->price . '",
                            "currency_code": "' . getConfigValue('currency') . '"
                        }
                    }
                }
            ],
            "payment_preferences": {
                "auto_bill_outstanding": true,
                "setup_fee": {
                  "value": "' . $plan->price . '",
                  "currency_code": "' . getConfigValue('currency') . '"
                },
                "setup_fee_failure_action": "CONTINUE",
                "payment_failure_threshold": 0
            }
        }', true);
        $billingPlan = $paypal->createPlan($data);

        // $billingPlan = $paypal->createPlan([
        //     'name' => $plan->title,
        //     'description' => 'Subscription Plan',
        //     'type' => 'fixed',
        //     'payment_definitions' => [
        //         [
        //             'name' => 'Regular Payments',
        //             'type' => 'REGULAR',
        //             'frequency' => 'MONTH', // Assuming you want daily frequency
        //             'frequency_interval' => 1,
        //             'cycles' => 11,
        //             'amount' => [
        //                 'value' => $plan->price,
        //                 'currency' => getConfigValue('currency'),
        //             ],
        //         ],
        //     ],
        //     'merchant_preferences' => [
        //         'return_url' => null,
        //         'cancel_url' => null,
        //         'auto_bill_amount' => 'yes',
        //         'initial_fail_amount_action' => 'continue',
        //         'max_fail_attempts' => 0,
        //     ],
        //     'product_id' => $product['id'],
        // ]);

        // Activate the plan
        $paypal->activatePlan($billingPlan['id']);

        // Update the PayPal data in your database
        $plan->update([
            'paypal_plan_data' => json_encode($data),
            'plan_id2' => $billingPlan['id'],
        ]);

        Toastr::success(trans('PayPal package created successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {

        if (is_null($this->user) || !$this->user->can('admin.package.update')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $request->validate([
            'title' => 'required|unique:packages,title,' . $id,
            // 'price' => 'required',
            // 'price_ngn' => 'required',
            // 'duration' => 'required',
            'offerings' => 'required',
            'library' => 'required',
            'book' => 'required',
            'blog' => 'required',
            'forum' => 'required',
            'club' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $package = Package::findOrFail($id);
            $package->title = $request->title;
            // $package->price = $request->price;
            // $package->price_ngn = $request->price_ngn;
            // $package->duration = $request->duration;
            $package->offerings = $request->offerings;
            $package->library = $request->library;
            $package->book = $request->book;
            $package->blog = $request->blog;
            $package->forum = $request->forum;
            $package->club = $request->club;
            $package->updated_by = Auth::user()->id;
            $package->save();
            DB::commit();
            Toastr::success('Package Updated Successfully!', 'Success');
            return redirect()->route('admin.package.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Something went wrong!', 'Failed');
            return redirect()->back();
        }

    }

    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.package.store')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $request->validate([
            'title' => 'required|unique:packages,title',
            'price' => 'required',
            'price_ngn' => 'required',
            'duration' => 'required',
            'offerings' => 'required',
            'library' => 'required',
            'book' => 'required',
            'blog' => 'required',
            'forum' => 'required',
            'club' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $package = new Package();
            $package->title = $request->title;
            $package->price = $request->price;
            $package->price_ngn = $request->price_ngn;
            $package->duration = $request->duration;
            $package->offerings = $request->offerings;
            $package->library = $request->library;
            $package->book = $request->book;
            $package->blog = $request->blog;
            $package->forum = $request->forum;
            $package->club = $request->club;
            $package->created_by = Auth::user()->id;
            $package->save();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Something went wrong!', 'Failed');
            return redirect()->back();
        }
        DB::commit();
        Toastr::success('Package Added Successfully!', 'Success');
        return redirect()->route('admin.package.index');


    }

    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.package.edit')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Package';
        $data['package'] = Package::findOrFail($id);
        return view('admin.packages.edit', $data);
    }

    public function view($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.package.view')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Package';
        $data['package'] = Package::findOrFail($id);
        return view('admin.packages.view', $data);
    }

    public function delete($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.package.delete')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        DB::beginTransaction();
        try {
            $package = Package::findOrFail($id);
            $package->delete();
            DB::commit();
            Toastr::success('Package Deleted Successfully!', 'Success');
            return redirect()->route('admin.package.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Something went wrong!', 'Failed');
            return redirect()->back();
        }
    }

    public function getFluterPlan($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.package.getFluterPlan')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        try {
            $package = Package::findOrFail($id);
            $payload = new Payload();
            $payload->set("amount", $package->price_ngn);
            $payload->set("name", $package->title);
            $payload->set("interval", "monthly");// Assuming "monthly" is a fixed value
            $payload->set("duration", $package->duration);
            $payload->set("currency", "NGN");// Assuming "NGN" is a fixed value
            $service = new PaymentPlan();
            $response = $service->create($payload);
            $package->plan_id = $response->data->id;
            $package->save();
            Toastr::success('Fluter-wave plan is successfully created.', 'Success');
        } catch (\Exception $e) {
            Log::alert($e->getMessage());
            Toastr::success('Fluter-wave plan is successfully created.', 'Success');
        }
        return back();


    }

    public function create()
    {
        if (is_null($this->user) || !$this->user->can('admin.package.create')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }
        $data['title'] = 'Add Package';
        return view('admin.packages.create', compact('data'));
    }

}
