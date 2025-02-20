<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogCategoryMap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class BlogCategoryController extends Controller
{
    public $user;
    protected $bcat;
    public function __construct(BlogCategory $bcat)
    {
        $this->bcat     = $bcat;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    public function index()
    {

        if (is_null($this->user) || !$this->user->can('admin.blog.category.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title']  = 'Blog Category';
        $data['rows']   = BlogCategory::orderBy('order_number', 'asc')->get();
        return view('admin.blog.category.index', compact('data'));
    }

    public function create()
    {
        if (is_null($this->user) || !$this->user->can('admin.blog.category.create')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Blog Category';
        return view('admin.blog.category.create', compact('data'));
    }
    public function store(Request $request)
    {

        if (is_null($this->user) || !$this->user->can('admin.blog.category.store')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        DB::beginTransaction();
        try {
            $this->validate($request, [
                'name'  => 'required|max:100',
                'order_number'  => 'required',
                'status' => 'required'
            ]);

            $slug = Str::slug($request->name);
            $check_slug = BlogCategory::where('slug', $slug)->first();
            
            if ($check_slug) {
                $uniqueId = Str::uuid()->toString();
                $slug = $slug . '-' . $uniqueId;
            }

            $blog_category = new BlogCategory();
            $blog_category->name = $request->name;
            $blog_category->slug = $slug;
            $blog_category->order_number = $request->order_number;
            $blog_category->status = $request->status;
            $blog_category->save();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Category not Created !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.blog.category.index');
        }
        DB::commit();
        Toastr::success(trans('Category Added Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.blog.category.index');
    }
    
    public function edit($id)
    {

        if (is_null($this->user) || !$this->user->can('admin.blog.category.edit')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $category = BlogCategory::find($id);
        $data['title'] = 'Blog Category';
        $data['row'] = $category;
        return view('admin.blog.category.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('admin.blog.category.update')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        DB::beginTransaction();
        try {
            $this->validate($request, [
                'name'  => 'required|max:100',
                'order_number'  => 'required',
                'status' => 'required'
            ]);

            $slug = Str::slug($request->name);
            $check_slug = BlogCategory::where('slug', $slug)->first();
            
            if ($check_slug) {
                $uniqueId = Str::uuid()->toString();
                $slug = $slug . '-' . $uniqueId;
            }

            $blog_category = BlogCategory::find($id);
            $blog_category->name = $request->name;
            $blog_category->slug = $slug;
            $blog_category->order_number = $request->order_number;
            $blog_category->status = $request->status;
            $blog_category->save();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Category not Updated !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.blog.category.index');
        }
        DB::commit();
        Toastr::success(trans('Category Updated Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.blog.category.index');
    }

    public function delete($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.blog.category.delete')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        DB::beginTransaction();
        try {
            $blog = BlogCategoryMap::where('blog_category_id',$id)->first();
            if($blog)
            {
                Toastr::error(trans('Deleting this category is restricted due to linked data'), 'Error', ["positionClass" => "toast-top-right"]);
                return redirect()->route('admin.blog.category.index');
            }
            else
            {
                $category = BlogCategory::find($id);
                $category->delete();
            }

        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            Toastr::error(trans('Category not Deleted !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.blog.category.index');
        }
        DB::commit();
        Toastr::success(trans('Category Deleted Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.blog.category.index');
    }

}
