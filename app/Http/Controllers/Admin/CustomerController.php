<?php


namespace App\Http\Controllers\Admin;

use DB;
use File;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class CustomerController extends Controller
{

    protected $customer;
    public $user;

    public function __construct(User $customer)
    {
        $this->customer     = $customer;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }
    /**
     * Display a listing of the categories.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (is_null($this->user) || !$this->user->can('admin.customer.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Customer';
        $data['rows'] = User::oldest('id')->get();
        return view('admin.customer.index', compact('data'));
    }

    public function create(){
        if (is_null($this->user) || !$this->user->can('admin.customer.create')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }
        $data['title'] = 'Customer Create';
        $data['roles'] = Role::orderBy('name', 'asc')->get();
        return view('admin.customer.create', compact('data'));
    }

    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.customer.store')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $this->validate($request, [
            'name'           => 'required|max:100',
            'email'         => 'required|unique:users,email,except,id',
            'password'       => 'required|min:6|max:11',
            'phone'         => 'required|unique:users,phone',
            'address'       => 'nullable',
            'image'         => 'nullable',
            'status'        => 'required',
        ]);

        $imageName = null;
        DB::beginTransaction();
        try {
            if ($request->hasFile('image')) {
                $image = Image::make($request->file('image'));

                $imageName = time() . '-' . $request->file('image')->getClientOriginalName();
                $destinationPath = public_path('assets/images/customer/');
                $image->save($destinationPath . $imageName);
            }

            $customer = new User();
            $customer->name          = $request->name;
            $customer->email         = $request->email;
            $customer->password      = md5($request->password);
            $customer->phone         = $request->phone;
            $customer->image         = $imageName;
            $customer->address       = $request->address;
            $customer->status        = $request->status;

            $customer->save();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            Toastr::error(trans('Customer not Created !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.customer.index');
        }
        DB::commit();
        Toastr::success(trans('Customer Created Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.customer.index');
    }

    public function edit($id)
    {

        if (is_null($this->user) || !$this->user->can('admin.customer.edit')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }
        $data['title'] = 'Customer Edit';
        $data['user'] = User::find($id);
        $data['role'] = Role::orderBy('name', 'asc')->get();
        return view('admin.customer.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('admin.customer.update')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }
        $customer = User::find($id);
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'name'           => 'required|max:100',
                'email'         => 'required|unique:users,email,'. $customer->id,
                'phone'          => 'required',
                'address'       => 'nullable',
                'image'         => 'nullable',
                'status'        => 'required',
            ]);

            // $slug = Str::slug($request->name);
            // $check_slug = User::where('id','!=',$id)->where('slug',$slug)->first();
            // if($check_slug){
            //     $slug = $slug.'_'.uniqid();
            // }

            $customer->name          = $request->name;
            $customer->email         = $request->email;
            $customer->phone         = $request->phone;
            $customer->address       = $request->address;
            $customer->status        = $request->status;

            if ($request->hasFile('image')) {
                $image = Image::make($request->file('image'));

                $imageName = time() . '-' . $request->file('image')->getClientOriginalName();
                $destinationPath = public_path('assets/images/customer/');
                $image->save($destinationPath . $imageName);
                $customer->image = $imageName;

            }
            $customer->save();

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Customer not Updated !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.customer.index');
        }
        DB::commit();
        Toastr::success(trans('Customer Updated Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.customer.index');
    }



    public function delete($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.customer.delete')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        DB::beginTransaction();
        try {
            $customer = User::find($id);
            if (File::exists('assets/images/customer/' . $customer->image)) {
                File::delete('assets/images/customer/' . $customer->image);
            }
            $customer->delete();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Customer not Deleted !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.customer.index');
        }
        DB::commit();
        Toastr::success(trans('Customer Deleted Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.customer.index');
    }

    public function view($id){
        if (is_null($this->user) ||!$this->user->can('admin.customer.view')) {
            abort(403, 'Sorry!! You are Unauthorized.');
        }
        $data['title'] = 'Customer View';
        $data['user'] = User::find($id);
        return view('admin.customer.view', compact('data'));
    }

}
