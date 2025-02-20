<?php

namespace App\Http\Controllers\User;

use App\Mail\Allmail;
use App\Models\Blog;
use App\Models\BlogTag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Models\BlogCategoryMap;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index()
    {
        if (auth()->user()->role_id != 1) {
            abort(404, 'Page not found');
        }
        $userBlog = userBlogPrivilege();
        if ($userBlog !== false && intval($userBlog->blog) !== 3) {
            Toastr::warning(trans('To access this privilege, please upgrade your plan'), 'Warning', ["positionClass" => "toast-top-right"]);
        } else {
            $data['title']  = 'Blogs';
            $userId         = Auth::user()->id;
            $data['rows']   = Blog::where('user_id', $userId)->withCount('comments')->latest('id')->get();
            return view('user.blog.index', compact('data'));
        }
        return redirect()->back();
    }

    public function create()
    {
        if (auth()->user()->role_id != 1) {
            abort(404, 'Page not found');
        }
        $userBlog = userBlogPrivilege();
        if ($userBlog !== false && intval($userBlog->blog) !== 3) {
            Toastr::warning(trans('To access this privilege, please upgrade your plan'), 'Warning', ["positionClass" => "toast-top-right"]);
        } else {
            $data['title'] = 'Create Blog Post';
            $data['rows'] = BlogCategory::where('status', '1')->get();
            return view('user.blog.create', compact('data'));
        }
        return redirect()->back();
    }

    public function store(Request $request)
    {
        if (auth()->user()->role_id != 1) {
            abort(404, 'Page not found');
        }
        $request->validate([
            'title'             => 'required|max:100',
            'category_id'       => 'required|array',
            'category_id.*'     => 'numeric',
            'tags'              => 'required',
            'descriptions'      => 'required',
            'short_descriptions' => 'required|max:250',
            'image'             => 'nullable|image',
        ]);


        DB::beginTransaction();
        $userBlog = userBlogPrivilege();
        if ($userBlog !== false && intval($userBlog->blog) !== 3) {
            Toastr::warning(trans('To access this privilege, please upgrade your plan'), 'Warning', ["positionClass" => "toast-top-right"]);
        } else {
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
                $blog->user_id              = Auth::id();
                $blog->title                = $request->title;
                $blog->slug                 = $slug;
                $blog->status               = 0;
                $blog->descriptions         = $request->descriptions;
                $blog->short_descriptions   = $request->short_descriptions;
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
                $name = Auth::user()->name . ' ' . Auth::user()->last_name;

                $data = [
                    'admin_email' => getSetting()->support_email,
                    'template' => 'BlogPost',
                    'subject' => 'New article posted on blog!',
                    'greeting' => 'Hello, Admin,',
                    'body' => 'A new blog has been posted from user - ' . $name . '. Please review and respond to the users post as soon as possible.',
                    'title' =>  $blog->title ,
                    'link' => route('admin.blog.index'),
                    'msg' => 'Click here to navigate to the blog page',
                    'thanks' => 'Thank you and stay with ' . ' ' . config('app.name'),
                    'site_url' => route('home'),
                    'site_name' => config('app.name'),
                    'copyright' => ' © ' . ' ' . Carbon::now()->format('Y') . config('app.name') . ' ' . 'All rights reserved.',
                    'footer' => '0',
                ];
                // if ($settings->app_mode == 'live') {
                Mail::to($data['admin_email'])->send(new Allmail($data));
                // }

            } catch (\Exception $e) {
                DB::rollback();
                Toastr::error(trans('Blog not Created !'), 'Error', ["positionClass" => "toast-top-right"]);
                return redirect()->route('user.blog.index');
            }
            DB::commit();
            Toastr::success(trans('Blog Added Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->route('user.blog.index');
        }
        return redirect()->back();
    }

    public function edit($id)
    {
        if (auth()->user()->role_id != 1) {
            abort(404, 'Page not found');
        }
        $userBlog = userBlogPrivilege();
        if ($userBlog !== false && intval($userBlog->blog) !== 3) {
            Toastr::warning(trans('To access this privilege, please upgrade your plan'), 'Warning', ["positionClass" => "toast-top-right"]);
        } else {
            $data['title']      = 'Edit Blog Post';
            $data['row']        = Blog::where('id', $id)->first();
            $data['categories'] = BlogCategory::where('status', '1')->get();
            $data['tags']       = BlogTag::where('blog_id', $id)->pluck('name')->toArray();
            $data['maps'] = BlogCategoryMap::where('blog_id', $id)->get();
            return view('user.blog.edit', compact('data'));
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role_id != 1) {
            abort(404, 'Page not found');
        }
        $request->validate([
            'title'                 => 'required|max:100',
            'category_id'           => 'required|array',
            'category_id.*'         => 'numeric',
            'tags'                  => 'required',
            'descriptions'          => 'required',
            'short_descriptions'    => 'required|max:250',
            'image'                 => 'nullable|image',
        ]);

        DB::beginTransaction();
        $userBlog = userBlogPrivilege();
        if ($userBlog !== false && intval($userBlog->blog) !== 3) {
            Toastr::warning(trans('To access this privilege, please upgrade your plan'), 'Warning', ["positionClass" => "toast-top-right"]);
        } else {
            try {

                $slug = Str::slug($request->title);
                $check_slug = blog::where('slug', $slug)->first();

                if ($check_slug) {
                    $uniqueId = Str::uuid()->toString();
                    $slug = $slug . '-' . $uniqueId;
                }

                $blog = Blog::findOrFail($id);
                $blog->user_id                = Auth::id();
                $blog->title                = $request->title;
                $blog->slug                 = $slug;
                $blog->status               = $request->status ?? 0;
                $blog->descriptions         = $request->descriptions;
                $blog->short_descriptions   = $request->short_descriptions;
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
                Toastr::error(trans('Blog not Updated !'), 'Error', ["positionClass" => "toast-top-right"]);
                return redirect()->route('user.blog.index');
            }
            DB::commit();
            Toastr::success(trans('Blog Updated Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->route('user.blog.index');
        }
        return redirect()->back();
    }

    public function delete($id)
    {
        if (auth()->user()->role_id != 1) {
            abort(404, 'Page not found');
        }
        DB::beginTransaction();
        $userBlog = userBlogPrivilege();
        if ($userBlog !== false && intval($userBlog->blog) !== 3) {
            Toastr::warning(trans('To access this privilege, please upgrade your plan'), 'Warning', ["positionClass" => "toast-top-right"]);
        } else {
            try {
                $blog = Blog::find($id);
                if (File::exists($blog->image)) {
                    File::delete($blog->image);
                }
                $blog->delete();
                DB::table('blog_category_map')->where('blog_id', $id)->delete();
                DB::table('blog_tags')->where('blog_id', $id)->delete();
                $comments = DB::table('blog_comments')->where('blog_post_id', $id)->get();
                foreach ($comments as $comment) {
                    DB::table('blog_comments')->where('id', $comment->id)->delete();
                }
            } catch (\Exception $e) {
                DB::rollback();
                Toastr::error(trans('Blog not Deleted !'), 'Error', ["positionClass" => "toast-top-right"]);
                return redirect()->route('user.blog.index');
            }
            DB::commit();
            Toastr::success(trans('Blog Deleted Successfully !'), 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->route('user.blog.index');
        }
        return redirect()->back();
    }
}
