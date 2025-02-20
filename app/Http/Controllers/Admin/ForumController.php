<?php

namespace App\Http\Controllers\Admin;

use App\Models\Report;
use Carbon\Carbon;
use App\Models\Forum;
use App\Mail\Allmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ForumCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ForumController extends Controller
{
    protected $forum;
    public $user;

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
        if (is_null($this->user) || !$this->user->can('admin.forum.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Forum Questions';
        $data['rows'] = Forum::withCount('getComment')->latest('id')->get();
        return view('admin.forum.questions.index', compact('data'));
    }

    public function view($id)
    {
        if (is_null($this->user) || !$this->user->can('admin.forum.view')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $data['title'] = 'Forum Questions';
        $forum = Forum::where('id',$id)->first();
        $data['row'] = $forum;
        return view('admin.forum.questions.view', compact('data'));
    }

    public function updateForumStatus(Request $request, $id)
    {
        if (is_null($this->user) || !$this->user->can('admin.forum.updateForumStatus')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $forum = Forum::find($id);
        if($forum->status ==1){
            $forum->status = 0;
            $forum->updated_by = Auth::id();
            $status = 0;
        }else{
            $forum->status = 1;
            $forum->approved_by = Auth::id();
            $forum->updated_by = Auth::id();
            $status = 1;
        }

        $result = $forum->save();
        if($result) {

            $body = ($status == 1) ? 'Your forum question, titled - "'.$forum->title.'" has been approved by the Admin.'
            : 'Unfortunately, your forum question titled - "'.$forum->title.'" has been declined by the Admin. If you have any concerns or would like further clarification, please contact support.';

            $subject = ($status == 1) ? 'Your Forum Question Has Been Approved.'
                : 'Your Forum Question Has Been Declined.';

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
        return back();
    }

    public function destroy($id){

        if (is_null($this->user) || !$this->user->can('admin.forum.delete')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        $forum = Forum::find($id);
        $forum->comments()->delete();
        $forum->tags()->delete();
        $forum->delete();
        Toastr::success(trans('Forum Question Deleted Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

    public function report()
    {
        $data = [
            'title' => 'User Reports',
            'reports' => Report::latest()->get(),
        ];

        return view('admin.forum.reports', $data);
    }

}
