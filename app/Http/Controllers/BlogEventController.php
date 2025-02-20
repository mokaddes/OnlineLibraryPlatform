<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Mail\Allmail;
use App\Models\BlogComment;
use App\Models\BlogPostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Facades\Toastr;

class BlogEventController extends Controller
{

    public function commentstore(Request $request)
    {
        $blogPrivilege = userPlanPrivilege()->blog ?? 0 ;
        if($blogPrivilege == 2 || $blogPrivilege == 3)
        {
            DB::beginTransaction();
            $this->validate($request, [
                'comments'        => 'required|max:1000',
            ]);
            try {
                $blog_comment               = new BlogComment();
                $blog_comment->user_id      = Auth::id();
                if ($request->has('comment_parent_id')) {
                    $blog_comment->comment_parent_id = $request->comment_parent_id;
                }
                $blog_comment->blog_post_id = $request->blog_post_id;
                $blog_comment->comments     = $request->comments;
                $blog_comment->status       = '0';
                $blog_comment->created_by   = Auth::id();
                $result = $blog_comment->save();
                if($result) {
                    $name = $blog_comment->getUser->name.' '.$blog_comment->getUser->last_name;
                    $data = [
                        'admin_email' => getSetting()->support_email,
                        'template'    => 'BlogPost',
                        'subject'     => 'New Blog Comment Submitted!',
                        'greeting'    => 'Hello, Admin,',
                        'body'        => 'A new comment on blog - "'.$blog_comment->getBlog->title.'" has been received from user - '.$name.'. Please review and respond to the users comment as soon as possible.',
                        'link'        => route('admin.blog.comment.index'),
                        'msg'         => 'Click here to navigate to the Blog Comment Index',
                        'thanks'      => 'Thank you and stay with ' . ' ' . config('app.name'),
                        'site_url'    => route('home'),
                        'footer'      => '0',
                        'site_name'   => config('app.name'),
                        'copyright'   => ' © ' . ' ' . Carbon::now()->format('Y') . config('app.name') . ' ' . 'All rights reserved.',
                    ];
                    // if ($settings->app_mode == 'live') {
                        Mail::to($data['admin_email'])->send(new Allmail($data));
                    // }
                }
            } catch (\Exception $e) {
                DB::rollback();
                Toastr::error($e->errors(), 'Error', ["positionClass" => "toast-top-right"]);
                return redirect()
                ->back()
                ->withInput($request->all());
            }
            DB::commit();
            Toastr::success(trans('Please wait for admin approval !'), 'Success', ["positionClass" => "toast-top-right"]);
        } else {
            $msg = (Auth::user()->role_id != 1) ? 'To access this feature, please log in with a reader/user account' :
                'To access this privilege, please upgrade your plan';

            Toastr::warning(trans($msg), 'Warning', ["positionClass" => "toast-top-right"]);
        }
        return redirect()->back();
    }
    public function commentLike(Request $request){
        $forumPrivilege = userPlanPrivilege()->forum ?? 0 ;

        if($forumPrivilege == 2 || $forumPrivilege == 3)
        {
        $status = true;
        $message = 'Thanks for your feedback';
        $record = 1;
        DB::beginTransaction();
        try {
            $commentid = $request->input('commentid');
            $blogid = $request->input('blogid');
            $like = $request->input('like');
            $auth_id = Auth::id();
            $check = DB::table('blog_post_likes')->where('user_id',$auth_id)->where('comment_id',$commentid)->first();


            if($check){
                $old_likedislike = $check->likedislike;
                if($old_likedislike == $like){
                    //need to remove
                    DB::table('blog_post_likes')->where('user_id',$auth_id)->where('comment_id',$commentid)->delete();
                    $record = 0;
                }else{
                    DB::table('blog_post_likes')->where('user_id',$auth_id)->where('comment_id',$commentid)->update(['likedislike' => $like,'updated_by' => $auth_id,'updated_at'=>now() ]);
                }
            }else{
                DB::table('blog_post_likes')->insert([
                    'user_id' => $auth_id,
                    'comment_id' => $commentid,
                    'blog_post_id' => $blogid,
                    'likedislike' => $like,
                    'created_at' => now(),
                    'created_by' => $auth_id,

                ]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();

            $data['status'] = false;
            $data['message'] = 'Something wrong please try again';
            return response()->json($data);
        }
        DB::commit();
       }
        else
        {
            $status = 'warning';
            $message = 'To access this privilege, please upgrade your plan';
            $record = 0;
        }
            $data['status'] = $status;
            $data['message'] = $message;
            $data['record'] = $record;
            return response()->json($data);

    }

    public function commentupdate($id, Request $request)
    {
        DB::beginTransaction();
        try {

            $comment =  BlogComment::find($id);
            if(isset($request->comments) && !empty($request->comments))
            {
                $comment->comments = $request->comments;
                $comment->status       = '0';
            }
            $result = $comment->save();
            if($result) {
                $name = $comment->getUser->name.' '.$comment->getUser->last_name;
                $data = [
                    'admin_email' => getSetting()->support_email,
                    'template'    => 'BlogPost',
                    'subject'     => 'New Blog Comment Submitted!',
                    'greeting'    => 'Hello, Admin,',
                    'body'        => 'A new comment on blog - "'.$comment->getBlog->title.'" has been received from user - '.$name.'. Please review and respond to the users comment as soon as possible.',
                    'link'        => route('admin.blog.comment.index'),
                    'msg'         => 'Click here to navigate to the Blog Comment Index',
                    'thanks'      => 'Thank you and stay with ' . ' ' . config('app.name'),
                    'site_url'    => route('home'),
                    'footer'      => '0',
                    'site_name'   => config('app.name'),
                    'copyright'   => ' © ' . ' ' . Carbon::now()->format('Y') . config('app.name') . ' ' . 'All rights reserved.',
                ];
                // if ($settings->app_mode == 'live') {
                    Mail::to($data['admin_email'])->send(new Allmail($data));
                // }
            }
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('An error occurred while updating the comment'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        DB::commit();
        Toastr::success(trans('Your comment updated successfully, Please wait for admin approval'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }
    public function commentDelete($id)
    {
        DB::beginTransaction();
        try {

            $blog = BlogComment::find($id);
            $commentIds = BlogComment::where('comment_parent_id', $blog->id)->pluck('id')->toArray();
            if (!empty($commentIds)) {
                BlogPostLike::whereIn('comment_id', $commentIds)->delete();
                BlogComment::whereIn('id', $commentIds)->delete();
                
                $secondLayerCommentIds = BlogComment::whereIn('comment_parent_id', $commentIds)->pluck('id')->toArray();

                if (!empty($secondLayerCommentIds)) {
                    BlogPostLike::whereIn('comment_id', $secondLayerCommentIds)->delete();
                    BlogComment::whereIn('id', $secondLayerCommentIds)->delete();
                }
            }
            BlogPostLike::where('comment_id', $blog->id)->delete();
            $blog->delete();

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('An error occurred while deleting the comment'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        DB::commit();
        Toastr::success(trans('Your comment deleted successfully'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();

    }

}
