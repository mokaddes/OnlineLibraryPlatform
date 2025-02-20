<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Models\BlogTag;
use App\Models\BlogCategory;
use App\Models\Forum;
use App\Models\ForumCategory;
use App\Models\ForumComment;
use App\Models\ForumTag;
use App\Traits\Notification;
use App\Traits\RepoResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    use RepoResponse, Notification;

    public function index(Request $request, $category = null)
    {
        $query = Forum::with(['categories', 'tags', 'comments' => function ($q) {
            $q->with('likeDislikes');
        }])->where('status', 1)->orderBy('id', 'desc');
        if ($category) {
            $query->whereHas('categories', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }
        if (!empty($request->search)) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if (!empty($request->tag)) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }
        $forums = $query->get();

        if ($forums && $forums->count() > 0) {
            return $this->apiResponse(1, 200, 'forums is successfully found.', '', $forums);
        }
        return $this->apiResponse(0, 422, 'forums is not found.', '', []);

    }

    public function details($id)
    {
        $forum = Forum::with(['categories', 'tags', 'comments' => function ($q) {
            $q->with('likeDislikes');
        }])->where('status', 1)->where('id', $id)->first();
        if ($forum) {
            return $this->apiResponse(1, 200, 'forum is successfully found.', '', $forum);
        }
        return $this->apiResponse(0, 422, 'forum is not found.', '', []);
    }

    public function category()
    {
        $categories = ForumCategory::where('status', 1)->latest('order_number')->get();
        if ($categories && $categories->count() > 0) {
            return $this->apiResponse(1, 200, 'Forum category is successfully found.', '', $categories);
        }
        return $this->apiResponse(0, 422, 'Forum category is not found.', '', []);
    }

    public function tags()
    {
        $tags = ForumTag::all();
        if ($tags && $tags->count() > 0) {
            return $this->apiResponse(1, 200, 'Forum tags is successfully found.', '', $tags);
        }
        return $this->apiResponse(0, 422, 'Forum tags is not found.', '', []);
    }

    public function myQuestions()
    {
        $user = Auth::user();
        $questions = Forum::with(['categories', 'tags', 'comments' => function ($q) {
            $q->with('likeDislikes');
        }])->where('status', 1)->where('created_by', $user->id)->orderBy('id', 'desc')->get();
        if ($questions && $questions->count() > 0) {
            return $this->apiResponse(1, 200, 'questions is successfully found.', '', $questions);
        }
        return $this->apiResponse(0, 422, 'questions is not found.', '', []);

    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'descriptions' => 'required',
            'category_id' => 'required',
            'tags' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(0, 422, 'Validation Error', $validator->errors()->first(), [], $validator->errors());
        }
        $slug = Str::slug($request->title);
        $check_slug = Forum::where('id', '!=', $request->id)->where('slug', $slug)->first();
        if ($check_slug) {
            $slug = $slug . '_' . uniqid();
        }

        $user = Auth::user();
        $forum = Forum::create([
            'title' => $request->title,
            'slug' => $slug,
            'descriptions' => $request->descriptions,
            'category_id' => $request->category_id,
            'status' => 0,
            'created_by' => $user->id,
        ]);

        if ($forum) {
            $tagNames = explode(',', $request->tags);
            foreach ($tagNames as $tagName) {
                ForumTag::create(['name' => $tagName, 'forum_id' => $forum->id]);
            }

            return $this->apiResponse(1, 200, 'forum is successfully created.', '', $forum);
        }
        return $this->apiResponse(0, 422, 'forum is not created.', '', []);

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'descriptions' => 'required',
            'category_id' => 'required',
            'tags' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(0, 422, 'Validation Error', $validator->errors()->first(), [], $validator->errors());
        }

        $forum = Forum::find($id);
        if (!$forum) {
            return $this->apiResponse(0, 422, 'forum is not found.', '', []);
        }
        $slug = Str::slug($request->title);
        $check_slug = Forum::where('id', '!=', $request->id)->where('slug', $slug)->first();
        if ($check_slug) {
            $slug = $slug . '_' . uniqid();
        }
        $forum->title = $request->title;
        $forum->slug = $slug;
        $forum->descriptions = $request->descriptions;
        $forum->category_id = $request->category_id;
        $forum->updated_by = Auth::user()->id;
        $forum->save();

        $forum->tags()->delete();
        $tagNames = explode(',', $request->tags);
        foreach ($tagNames as $tagName) {
            ForumTag::create(['name' => $tagName, 'forum_id' => $forum->id]);
        }
        return $this->apiResponse(1, 200, 'forum is successfully updated.', '', $forum);
    }

    public function delete($id)
    {
        $forum = Forum::find($id);
        if (!$forum) {
            return $this->apiResponse(0, 422, 'forum is not found.', '', []);
        }
        $forum->delete();
        return $this->apiResponse(1, 200, 'forum is successfully deleted.', '', $forum);
    }

    public function comment(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'comments' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(0, 422, 'Validation Error', $validator->errors()->first(), [], $validator->errors());
        }
        $user = Auth::user();
        $forum = Forum::find($id);
        $comment = new ForumComment();
        $comment->comments = $request->comments;
        $comment->comment_parent_id = $request->comment_parent_id ?? 0;
        $comment->user_id = $user->id;
        $comment->created_by = $user->id;
        $comment->forum_id = $forum->id;
        $comment->status = 0;
        $comment->save();
        $title = $user->name . ' replies to your forum.';
        $this->saveUserNotification($title, 'frontend.forum.details', 'forum', 'user', $comment->getForum->user_id);
        $this->sendUserPushNotification([$comment->getForum->user_id], 'Forum replay' ,$title, 'frontend.forum.details');

        return $this->apiResponse(1, 200, 'comment is successfully created.', '', $comment);
    }

    public function likeDislike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'likedislike' => 'required',
            'forum_post_id' => 'required',
            'comment_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(0, 422, 'Validation Error', $validator->errors()->first(), [], $validator->errors());
        }
        $user = Auth::user();
        $comment = ForumComment::find($request->comment_id);
        if (!$comment) {
            return $this->apiResponse(0, 422, 'comment is not found.', '', []);
        }
        $comment->likeDislikes()->createOrUpdate(
            [
                'user_id' => $user->id,
                'forum_post_id' => $request->forum_post_id,
            ],
            [
                'likedislike' => $request->likedislike,
            ]
        );
        $action =  $request->likedislike == 1 ? 'Like' : 'Dislike';
        $title = Auth::user()->name . ' ' .$action. ' on your comment.';
        $this->saveUserNotification($title, 'frontend.forum.details', 'forum', 'user', $comment->user_id);
        $this->sendUserPushNotification([$user->id], 'Forum comment' ,$title, route('frontend.forum.details'));
        return $this->apiResponse(1, 200, 'likedislike is successfully created.', '', $comment);

    }
}
