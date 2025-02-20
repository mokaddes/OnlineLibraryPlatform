<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ForumCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ForumCategoryController extends Controller
{
  public $user;
  protected $fcat;
  public function __construct(ForumCategory $fcat)
  {
    $this->fcat     = $fcat;
    $this->middleware(function ($request, $next) {
      $this->user = Auth::guard('admin')->user();
      return $next($request);
    });
  }
  public function index()
  {
    if (is_null($this->user) || !$this->user->can('admin.forum.category.index')) {
      abort(403, 'Sorry !! You are Unauthorized.');
    }
    $data['title'] = 'Forum Category';
    $data['rows']   = ForumCategory::orderBy('order_number', 'asc')->get();
    return view('admin.forum.category.index', compact('data'));
  }

  public function create()
  {
    if (is_null($this->user) || !$this->user->can('admin.forum.category.create')) {
      abort(403, 'Sorry !! You are Unauthorized.');
    }
    $data['title'] = 'Forum Category';
    return view('admin.forum.category.create', compact('data'));
  }

  public function store(Request $request)
  {
    if (is_null($this->user) || !$this->user->can('admin.forum.category.store')) {
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
      $check_slug = ForumCategory::where('slug', $slug)->first();
      
      if ($check_slug) {
          $uniqueId = Str::uuid()->toString();
          $slug = $slug . '-' . $uniqueId;
      }

      $forum_category = new ForumCategory();
      $forum_category->name = $request->name;
      $forum_category->slug = $slug;
      $forum_category->order_number = $request->order_number;
      $forum_category->status = $request->status;
      $forum_category->save();
    } catch (\Exception $e) {
      DB::rollback();
      Toastr::error(trans('Category not Created !'), 'Error', ["positionClass" => "toast-top-right"]);
      return redirect()->route('admin.forum.category.index');
    }
    DB::commit();
    Toastr::success(trans('Category Added Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
    return redirect()->route('admin.forum.category.index');
  }

  public function edit($id)
  {
    if (is_null($this->user) || !$this->user->can('admin.forum.category.edit')) {
      abort(403, 'Sorry !! You are Unauthorized.');
    }
    $category = ForumCategory::find($id);
    $data['title'] = 'Forum Category';
    $data['row'] = $category;
    return view('admin.forum.category.edit', compact('data'));
  }

  public function update(Request $request, $id)
  {
    if (is_null($this->user) || !$this->user->can('admin.forum.category.update')) {
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
      $check_slug = ForumCategory::where('slug', $slug)->first();
      
      if ($check_slug) {
          $uniqueId = Str::uuid()->toString();
          $slug = $slug . '-' . $uniqueId;
      }

      $blog_category = ForumCategory::find($id);
      $blog_category->name = $request->name;
      $blog_category->slug =  $slug;
      $blog_category->order_number = $request->order_number;
      $blog_category->status = $request->status;
      $blog_category->save();
    } catch (\Exception $e) {
      DB::rollback();
      Toastr::error(trans('Category not Updated !'), 'Error', ["positionClass" => "toast-top-right"]);
      return redirect()->route('admin.forum.category.index');
    }
    DB::commit();
    Toastr::success(trans('Category Updated Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
    return redirect()->route('admin.forum.category.index');
  }

  public function delete($id)
  {
    if (is_null($this->user) || !$this->user->can('admin.forum.category.delete')) {
      abort(403, 'Sorry !! You are Unauthorized.');
    }
    DB::beginTransaction();
    try {
      $category = ForumCategory::find($id);
      $category->delete();
    } catch (\Exception $e) {
      dd($e);
      DB::rollback();
      Toastr::error(trans('Category not Deleted !'), 'Error', ["positionClass" => "toast-top-right"]);
      return redirect()->route('admin.forum.category.index');
    }
    DB::commit();
    Toastr::success(trans('Category Deleted Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
    return redirect()->route('admin.forum.category.index');
  }
}
