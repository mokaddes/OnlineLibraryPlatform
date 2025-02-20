<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Blog;
use App\Models\User;
use App\Mail\Allmail;
use App\Models\BlogComment;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Symfony\Component\Console\Input\Input;

class BlogCommentController extends Controller
{
    public $user;
    protected $blog;
    public function __construct(Blog $blog)
    {
        $this->blog     = $blog;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    public function index()
    {
        if (is_null($this->user) || !$this->user->can('admin.blog.comment.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Blog Comments';
        $comments = BlogComment::with('likes')->latest('id')->get();

        foreach ($comments as $comment) {
            $comment['like'] = $comment->likes->where('likedislike', '1')->count();
            $comment['dislike'] = $comment->likes->where('likedislike', '0')->count();
            $replyCount = BlogComment::where('comment_parent_id', $comment->id)->count();
            $comment['replyCount'] = $replyCount;
        }
        $data['rows'] = $comments;
        return view('admin.blog.comments.index', compact('data'));
    }
    public function view($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.blog.comment.view')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Blog Comment';
        $blog = BlogComment::with('getBlog')->where('id',$id)->first();
        $data['row'] = $blog;
        return view('admin.blog.comments.view', compact('data'));
    }

    public function updateCommentStatus(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('admin.blog.comment.updateCommentStatus')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $blog = BlogComment::find($id);

        if ($blog->status == 1) {
            $commentIds = BlogComment::where('comment_parent_id', $blog->id)->pluck('id')->toArray();
            // Update status for the second layer of comments
            BlogComment::whereIn('id', $commentIds)->update(['status' => 0]);
            // Update status for the second layer of comments
            BlogComment::whereIn('comment_parent_id', $commentIds)->update(['status' => 0]);
            // Update status for the first layer of comments
            $blog->status = 0;
            $status = 0;
            $blog->save();

        } else {
            $commentIds = BlogComment::where('id', $blog->comment_parent_id)->where('status','0')->first();
            if($commentIds) {
                Toastr::warning(trans('This comment cannot be unpublished because it is associated with an unpublished comment'), 'Warning', ["positionClass" => "toast-top-right"]);
                return redirect()->back();
            } else {
                $blog->status = 1;
                $status = 1;
            }
            $blog->approved_by = Auth::id();
        }
        $result = $blog->save();

        if($result) {

            $body = ($status == 1) ? 'Your comment on blog, titled - "'.$blog->getBlog->title.'" has been approved by the Admin.'
            : 'Unfortunately, your comment on blog titled - "'. $blog->getBlog->title .'" has been declined by the Admin. If you have any concerns or would like further clarification, please contact support.';
        
            $subject = ($status == 1) ? 'Comment On Blog Has Been Approved.'
                : 'Comment On Blog Has Been Declined.';

            $name = $blog->getUser->name.' '.$blog->getUser->last_name;
            $data = [
                'user_email'  => $blog->getUser->email,
                'template'    => 'BlogPost',
                'subject'     => $subject,
                'greeting'    => 'Hello, '.$name,
                'body'        => $body,
                'link'        => route('frontend.blogs'),
                'msg'         => 'Click here to navigate to the Blog page',
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
        return redirect(route('admin.blog.comment.index'));
    }

    public function destroy($id){

        if (is_null($this->user) || !$this->user->can('admin.blog.comment.delete')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        BlogComment::find($id)->delete();
        Toastr::success(trans('Comment Deleted Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

}
