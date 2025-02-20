<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Forum;
use App\Mail\Allmail;
use App\Models\ForumComment;
use App\Traits\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForumEventController extends Controller
{
    use Notification;
    public function commentstore(Request $request)
    {
        $forumPrivilege = userPlanPrivilege()->forum ?? 0 ;
        if($forumPrivilege == 2 || $forumPrivilege == 3)
        {
            DB::beginTransaction();
            $this->validate($request, [
                'comments'        => 'required|max:1000',
            ]);
            try {
                $forum_comment               = new ForumComment();
                $forum_comment->user_id      = Auth::id();
                if ($request->has('comment_parent_id')) {
                    $forum_comment->comment_parent_id = $request->comment_parent_id;
                }
                $forum_comment->forum_id     = $request->forum_id;
                $forum_comment->comments     = $request->comments;
                $forum_comment->status       = '0';
                $forum_comment->created_by   = Auth::id();
                $result = $forum_comment->save();
                $title = Auth::user()->name . ' replies to your forum.';
                $routeString = 'frontend.forum.details,' . $forum_comment->getForum->slug;
                $this->saveUserNotification($title, $routeString, 'forum', 'user', $forum_comment->getForum->created_by);
                $this->sendUserPushNotification([$forum_comment->getForum->created_by], 'Forum replies' ,$title, $routeString);


                if($result) {
                    $name = $forum_comment->getUser->name.' '.$forum_comment->getUser->last_name;
                    $data = [
                        'admin_email' => getSetting()->support_email,
                        'template'    => 'forumQuestion',
                        'subject'     => 'New Forum Comment Submitted!',
                        'greeting'    => 'Hello, Admin,',
                        'body'        => 'A new comment on Forum - "'.$forum_comment->getForum->title.'" has been received from user - '.$name.'. Please review and respond to the users comment as soon as possible.',
                        'link'        => route('admin.forum.comment.index'),
                        'msg'         => 'Click here to navigate to the Forum Comment Index',
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
        }
        else
        {
            $msg = (Auth::user()->role_id != 1) ? 'To access this feature, please log in with a reader/user account'
            : 'To access this privilege, please upgrade your plan';

            Toastr::warning(trans($msg), 'Warning', ["positionClass" => "toast-top-right"]);
        }
        return redirect()->back();

    }

    public function commentLike(Request $request)
    {

        $forumPrivilege = userPlanPrivilege()->forum ?? 0 ;

        if($forumPrivilege == 2 || $forumPrivilege == 3)
        {
            $status = true;
            $message = 'Thanks for your feedback';
            $record = 1;
            DB::beginTransaction();
            try {
                $commentid = $request->input('commentid');
                $forumid = $request->input('forumid');
                $like = $request->input('like');
                $auth_id = Auth::id();
                $check = DB::table('forum_post_likes')->where('user_id',$auth_id)->where('comment_id',$commentid)->first();

                if($check){
                    $old_likedislike = $check->likedislike;
                    if($old_likedislike == $like){
                        //need to remove
                        DB::table('forum_post_likes')->where('user_id',$auth_id)->where('comment_id',$commentid)->delete();
                        $record = 0;
                    }else{
                        DB::table('forum_post_likes')->where('user_id',$auth_id)->where('comment_id',$commentid)->update(['likedislike' => $like,'updated_by' => $auth_id,'updated_at'=>now() ]);
                    }
                }else{
                    DB::table('forum_post_likes')->insert([
                        'user_id' => $auth_id,
                        'comment_id' => $commentid,
                        'forum_post_id' => $forumid,
                        'likedislike' => $like,
                        'created_at' => now(),
                        'created_by' => $auth_id,

                    ]);
                    $comment = ForumComment::with('getForum')->find($commentid);
                    $action = $like == 1 ? 'Like' : 'Dislike';
                    $title = Auth::user()->name . ' ' .$action. ' on your comment.';
                    $routeString = 'frontend.forum.details,' . $comment->getForum->slug;
                    $this->saveUserNotification($title, $routeString, 'forum', 'user', $comment->user_id);
                    $this->sendUserPushNotification([$comment->user_id], 'Forum comment' ,$title, $routeString);

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
}
