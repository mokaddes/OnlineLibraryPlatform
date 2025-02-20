<?php


namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductCategoryMap;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    protected $category;
    public $user;

    public function __construct(Category $category)
    {
        $this->category     = $category;
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
        if (is_null($this->user) || !$this->user->can('admin.category.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Categories';
        $data['rows'] = Category::orderBy('order_number', 'asc')->get();
        return view('admin.category.index', compact('data'));
    }

    public function create()
    {
        if (is_null($this->user) || !$this->user->can('admin.category.create')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Add Category';
        return view('admin.category.create', compact('data'));
    }

    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.category.store')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        DB::beginTransaction();
        try {
            $this->validate($request, [
                'name'          => 'required|max:100',
                'order_number'  => 'required|numeric',
                'status'        => 'required',
                'logo'        => 'required'
            ]);

            $slug = Str::slug($request->name);
            $check_slug = Category::where('slug', $slug)->first();
            
            if ($check_slug) {
                $uniqueId = Str::uuid()->toString();
                $slug = $slug . '-' . $uniqueId;
            }

            if ($request->logo) {
                $logo = $request->file('logo');
                $base_name = preg_replace('/\..+$/', '', $logo->getClientOriginalName());
                $base_name = explode(' ', $base_name);
                $base_name = implode('-', $base_name);
                $base_name = Str::lower($base_name);
                $image_name = $base_name . "-" . uniqid() . "." . $logo->getClientOriginalExtension();
                $file_path = 'uploads/category';
                $logo->move(public_path($file_path), $image_name);
            }
            $category = new Category();
            $category->name         = $request->name;
            $category->slug         = $slug;
            $category->order_number = $request->order_number;
            $category->status       = $request->status;
            $category->logo         =  $file_path . '/' . $image_name;
            $category->save();

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Category not Created !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.category.create');
        }
        DB::commit();
        Toastr::success(trans('Category Created Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.category.index');
    }

    public function edit($id)
    {

        if (is_null($this->user) || !$this->user->can('admin.category.edit')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }
        $category = Category::find($id);
        $data['title'] = 'Category';
        $data['row'] = $category;
        return view('admin.category.edit', compact('data'));
    }

    public function update(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.category.update')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'name'          => 'required|max:100',
                'order_number'  => 'required|numeric',
                'status'        => 'required'
            ]);

            $slug = Str::slug($request->name);
            $check_slug = Category::where('slug', $slug)->first();
            
            if ($check_slug) {
                $uniqueId = Str::uuid()->toString();
                $slug = $slug . '-' . $uniqueId;
            }

            $category               = Category::find($request->id);
            $category->name         = $request->name;
            $category->slug         = $slug;
            $category->order_number = $request->order_number;
            $category->status       = $request->status;

            if ($request->logo) {
                $logo = $request->file('logo');
                $base_name = preg_replace('/\..+$/', '', $logo->getClientOriginalName());
                $base_name = explode(' ', $base_name);
                $base_name = implode('-', $base_name);
                $base_name = Str::lower($base_name);
                $image_name = $base_name . "-" . uniqid() . "." . $logo->getClientOriginalExtension();
                $file_path = 'uploads/category';
                $logo->move(public_path($file_path), $image_name);
                $category->logo         =  $file_path . '/' . $image_name;
            }

            $category->save();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Category not Updated !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.category.edit',$request->id);
        }
        DB::commit();
        Toastr::success(trans('Category Updated Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.category.index');
    }



    public function delete($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.category.delete')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }
        DB::beginTransaction();
        try {
            $Book = ProductCategoryMap::where('product_category_id',$id)->exists();
            if($Book)
            {
                Toastr::error(trans('Deleting this category is restricted due to linked data'), 'Error', ["positionClass" => "toast-top-right"]);
                return redirect()->route('admin.category.index');
            }
            else
            {
                $category = Category::find($id);
                if (file_exists($category->logo)) {
                   unlink($category->logo);
                }
                $category->delete();
            }

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Category not Deleted !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.category.index');
        }
        DB::commit();
        Toastr::success(trans('Category Deleted Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.category.index');
    }





}
