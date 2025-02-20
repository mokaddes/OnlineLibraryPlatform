<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Forum;
use App\Mail\Allmail;
use Illuminate\Support\Str;
use App\Models\ForumComment;
use Illuminate\Http\Request;
use App\Models\ForumCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForumCommentController extends Controller
{
    public $user;
    protected $forum;
    public function __construct(Forum $forum)
    {
        $this->forum     = $forum;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    public function index()
    {
        if (is_null($this->user) || !$this->user->can('admin.forum.comment.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Forum Comments';
        $comments = ForumComment::whereHas('getForum')->with('likeDislikes')->latest('id')->get();

        foreach ($comments as $comment) {
            $comment['like'] = $comment->likeDislikes->where('likedislike', '1')->count();
            $comment['dislike'] = $comment->likeDislikes->where('likedislike', '0')->count();
            $replyCount = ForumComment::where('comment_parent_id', $comment->id)->count();
            $comment['replyCount'] = $replyCount;
        }
        $data['rows'] = $comments;
        return view('admin.forum.comments.index', compact('data'));
    }

    public function updateForumCommentStatus(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('admin.forum.comment.updateForumCommentStatus')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $forum = ForumComment::find($id);
        if($forum->status ==1){
            $commentIds = ForumComment::where('comment_parent_id', $forum->id)->pluck('id')->toArray();
            // Update status for the second layer of comments
            ForumComment::whereIn('id', $commentIds)->update(['status' => 0]);
            // Update status for the second layer of comments
            ForumComment::whereIn('comment_parent_id', $commentIds)->update(['status' => 0]);
            // Update status for the first layer of comments

            $forum->status = 0;
            $forum->updated_by = Auth::id();
            $forum->approved_at = now();
            $status = 0;
        }else{
            $commentIds = ForumComment::where('id', $forum->comment_parent_id)->where('status','0')->first();
            if($commentIds) {
                Toastr::warning(trans('This comment cannot be unpublished because it is associated with an unpublished comment'), 'Warning', ["positionClass" => "toast-top-right"]);
                return redirect()->back();
            } else {
                $forum->status = 1;
                $forum->approved_by = Auth::id();
                $forum->approved_at = now();
                $forum->updated_by = Auth::id();
                $status = 1;
            }
        }
        $result = $forum->save();

        if($result) {

            $body = ($status == 1) ? 'Your comment on forum question, titled - "'.$forum->title.'" has been approved by the Admin.'
            : 'Unfortunately, your comment on forum question titled - "'. $forum->title .'" has been declined by the Admin. If you have any concerns or would like further clarification, please contact support.';

            $subject = ($status == 1) ? 'Comment On Forum Question Has Been Approved.'
                : 'Comment On Forum Question Has Been Declined.';

            $name = $forum->getUser->name.' '.$forum->getUser->last_name;
            $data = [
                'user_email'  => $forum->getUser->email,
                'template'    => 'forumQuestion',
                'subject'     => $subject,
                'greeting'    => 'Hello, '.$name,
                'body'        => $body,
                'link'        => route('frontend.forum'),
                'msg'         => 'Click here to navigate to the Forum page',
                'thanks'      => 'Thank you and stay with ' . ' ' . config('app.name'),
                'site_url'    => route('home'),
                'site_name'   => config('app.name'),
                'footer'      => '1',
                'copyright'   => ' © ' . ' ' . Carbon::now()->format('Y') . config('app.name') . ' ' . 'All rights reserved.',
            ];
            // if ($settings->app_mode == 'live') {
                Mail::to($data['user_email'])->send(new Allmail($data));
            // }
        }
        return redirect(route('admin.forum.comment.index'));
    }

    public function view($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.forum.comment.view')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Forum Comment';
        $data['row'] = ForumComment::with('replies','getForum')->find($id);
        return view('admin.forum.comments.view', compact('data'));
    }

    public function destroy($id){

        if (is_null($this->user) || !$this->user->can('admin.forum.comment.delete')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        ForumComment::find($id)->delete();
        Toastr::success(trans('Comment Deleted Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }
}
