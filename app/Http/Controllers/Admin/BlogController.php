<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\BlogTag;
use App\Models\BlogCategory;
use App\Models\BlogCategoryMap;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    protected $blog;
    public $user;

    public function __construct(Blog $blog)
    {
        $this->blog     = $blog;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.blog.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Blog';
        $query = Blog::with('category')->orderBy('id', 'desc');
        if ($request->has('posted_by') && $request->posted_by !== null) {
            if ($request->posted_by == 1) {
                $query->whereNotNull('user_id');
            } elseif ($request->posted_by == 0) {
                $query->whereNull('user_id');
            } 
        }
        $data['rows'] = $query->get();
        return view('admin.blog.index', compact('data'));
    }

    public function create()
    {
        if (is_null($this->user) || !$this->user->can('admin.blog.create')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Blog';
        $data['rows'] = BlogCategory::where('status', '1')->get();
        return view('admin.blog.create', compact('data'));
    }

    public function store(Request $request)
    {

        if (is_null($this->user) || !$this->user->can('admin.blog.store')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $request->validate([
            'title'       => 'required|max:100',
            'category_id' => 'required|array',
            'category_id.*' => 'numeric',
            'tags'        => 'required',
            'status'      => 'required',
            'descriptions' => 'required',
            'short_descriptions' => 'required',
            'image' => 'nullable|image',
        ]);


        DB::beginTransaction();
        try {

            $slug = Str::slug($request->title);
            $check_slug = blog::where('slug', $slug)->first();

            if ($check_slug) {
                $uniqueId = Str::uuid()->toString();
                $slug = $slug . '-' . $uniqueId;
            }

            if ($request->hasFile('image')) {
                $image      = $request->file('image');
                $fileExtension = strtolower($image->getClientOriginalExtension());
                $base_name = preg_replace('/\..+$/', '', $image->getClientOriginalName());
                $base_name = str_replace(' ', '-', $base_name);
                $base_name = Str::lower($base_name);
                $image_name = $base_name . "-" . uniqid() . "." . $fileExtension;
                $file_path = 'uploads/blog';
                $image->move(public_path($file_path), $image_name);
            }
            $blog = new blog();
            $blog->title            = $request->title;
            $blog->slug             = $slug;
            $blog->status           = $request->status;
            if ($request->has('is_top')) {
                $blog->is_top = $request->is_top;
            }
            $blog->descriptions         = $request->descriptions;
            $blog->short_descriptions    = $request->short_descriptions;
            $blog->image                =  $file_path . '/' . $image_name;
            $blog->save();
            if ($request->tags) {
                $tagsarr = explode(',', $request->tags);
                foreach ($tagsarr as $key => $value) {
                    $blog_tags = new BlogTag();
                    $blog_tags->blog_id = $blog->id;
                    $blog_tags->slug    = Str::slug($value);
                    $blog_tags->name    = $value;
                    $blog_tags->save();
                }
            }
            $category_ids = $request->category_id;
            foreach ($category_ids as $category_id) {
                $blog_map = new BlogCategoryMap();
                $blog_map->blog_id = $blog->id;
                $blog_map->blog_category_id = $category_id;
                $blog_map->save();
            }
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            Toastr::error(trans('Blog not Created !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.blog.index');
        }
        DB::commit();
        Toastr::success(trans('Blog Added Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.blog.index');
    }

    public function edit($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.blog.edit')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Blog Edit';
        $data['row'] = Blog::where('id', $id)->first();
        $data['categories'] = BlogCategory::where('status', '1')->get();
        $data['maps'] = BlogCategoryMap::where('blog_id', $id)->get();
        $data['tags'] = BlogTag::where('blog_id', $id)->pluck('name')->toArray();
        return view('admin.blog.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('admin.blog.update')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $request->validate([
            'title'         => 'required|max:100',
            'category_id'   => 'required|array',
            'category_id.*' => 'numeric',
            'tags'          => 'required',
            'status'        => 'required',
            'descriptions'  => 'required',
            'short_descriptions' => 'required',
            'image' => 'nullable|image',
        ]);

        DB::beginTransaction();
        try {

            $slug = Str::slug($request->title);
            $check_slug = blog::where('slug', $slug)->first();

            if ($check_slug) {
                $uniqueId = Str::uuid()->toString();
                $slug = $slug . '-' . $uniqueId;
            }

            $blog = Blog::findOrFail($id);
            $blog->title                = $request->title;
            $blog->slug                 = $slug;
            $blog->status               = $request->status;
            $blog->descriptions         = $request->descriptions;
            $blog->short_descriptions   = $request->short_descriptions;
            if ($request->has('is_top')) {
                $blog->is_top = $request->is_top;
            }
            if ($request->image) {
                if (File::exists($blog->image)) {
                    File::delete($blog->image);
                }
                $image      = $request->file('image');
                $base_name  = preg_replace('/\..+$/', '', $image->getClientOriginalName());
                $base_name  = explode(' ', $base_name);
                $base_name  = implode('-', $base_name);
                $base_name  = Str::lower($base_name);
                $image_name = $base_name . "-" . uniqid() . "." . $image->getClientOriginalExtension();
                $file_path  = 'uploads/blog';
                $image->move(public_path($file_path), $image_name);
                $blog->image            =  $file_path . '/' . $image_name;
            }
            $blog->save();
            BlogTag::where('blog_id', $id)->delete();
            if ($request->tags) {
                $tagsarr = explode(',', $request->tags);
                foreach ($tagsarr as $key => $value) {
                    $blog_tags = new BlogTag();
                    $blog_tags->blog_id = $id;
                    $blog_tags->slug    = Str::slug($value);
                    $blog_tags->name    = $value;
                    $blog_tags->save();
                }
            }
            $categoryIds = $request->category_id;
            BlogCategoryMap::where('blog_id', $id)->delete();
            foreach ($categoryIds as $categoryId) {
                $blogMap = new BlogCategoryMap();
                $blogMap->blog_id = $id;
                $blogMap->blog_category_id = $categoryId;
                $blogMap->save();
            }
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            Toastr::error(trans('Blog not Updated !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.blog.index');
        }
        DB::commit();
        Toastr::success(trans('Blog Updated Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.blog.index');
    }

    public function delete($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.blog.delete')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        DB::beginTransaction();
        try {
            $blog = Blog::find($id);
            if (File::exists($blog->image)) {
                File::delete($blog->image);
            }
            $blog->delete();
            DB::table('blog_category_map')->where('blog_id', $id)->delete();
            DB::table('blog_tags')->where('blog_id', $id)->delete();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Blog not Deleted !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.blog.index');
        }
        DB::commit();
        Toastr::success(trans('Blog Deleted Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.blog.index');
    }
}
