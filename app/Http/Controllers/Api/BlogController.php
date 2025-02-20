<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Models\BlogTag;
use App\Models\BlogCategory;
use App\Traits\RepoResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    use RepoResponse;
    public function index(Request $request, $category = null)
    {
        $query = Blog::with(['categories', 'tags'])->where('status', 1)->orderBy('id', 'desc');
        if ($category) {
            $query->whereHas('categories', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }
        if (!empty($request->search)) {
            $query->where('title', 'like', '%'. $request->search. '%');
        }
        if (!empty($request->tag)) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }
        $blogs = $query->get();

        if ($blogs && $blogs->count() > 0) {
            return $this->apiResponse(1, 200, 'blog is successfully found.', '', $blogs);
        }
        return $this->apiResponse(0, 422, 'blog is not found.', '', []);

    }

    public function details($id)
    {
        $blog = Blog::with(['categories', 'tags'])->where('id', $id)->first();
        if ($blog) {
            return $this->apiResponse(1, 200, 'blog is successfully found.', '', $blog);
        }
        return $this->apiResponse(0, 422, 'blog is not found.', '', []);
    }

    public function category()
    {
        $categories = BlogCategory::where('status', 1)->get();
        if ($categories && $categories->count() > 0) {
            return $this->apiResponse(1, 200, 'Bolg category is successfully found.', '', $categories);
        }
        return $this->apiResponse(0, 422, 'Blog category is not found.', '', []);
    }

    public function tags()
    {
        $tags = BlogTag::all();
        if ($tags && $tags->count() > 0) {
            return $this->apiResponse(1, 200, 'Blog tags is successfully found.', '', $tags);
        }
        return $this->apiResponse(0, 422, 'Blog tags is not found.', '', []);
    }
}
