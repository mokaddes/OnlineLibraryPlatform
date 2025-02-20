<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Club;
use App\Mail\Allmail;
use App\Models\ClubPost;
use App\Models\ClubMember;
use Illuminate\Support\Str;
use App\Models\ClubComment;
use Illuminate\Http\Request;
use App\Traits\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class ClubController extends Controller
{

    use Notification;

    public function index(Request $request)
    {
        if (auth()->user()->role_id != 1) {
            abort(404, 'Page not found');
        }
        if (userPlanPrivilege() != false && userPlanPrivilege()->club != 1) {
            $user_id = Auth::user()->id;
            $data['title'] = 'Club';
            $data['rows'] = Club::withCount(['members' => function ($query) {
                $query->where('status', '1');
            }])
                ->where('status', '1')
                ->when($request->sort_by == 'oldest', function ($query) {
                    $query->orderBy('id', 'asc');
                })
                ->when($request->sort_by == 'highest_member', function ($query) {
                    $query->orderByDesc('members_count');
                }, function ($query) {
                    $query->orderBy('id', 'desc');
                })
                ->paginate(10);
            $data['clubs'] = Club::withCount(['members' => function ($query) {
                $query->where('status', 1);
            }])->whereIn('status', [1, 0])->whereHas('members', function ($query) use ($user_id) {
                $query->where('user_id', $user_id)->where('status', '1');
            })->orderBy('id', 'desc')->paginate(10);
            $data['posts'] = ClubPost::with('user')->whereHas('club', function ($query) use ($user_id) {
                $query->where('status', '1')->whereHas('members', function ($subQuery) use ($user_id) {
                    $subQuery->where('user_id', $user_id);
                });
            })->where('status', '1')->where('created_by', '!=', $user_id)->orderBy('created_at', 'desc')->get()->take(8);
            $data['posts_count'] = ClubPost::with('user')->whereHas('club', function ($query) use ($user_id) {
                $query->where('status', '1')->whereHas('members', function ($subQuery) use ($user_id) {
                    $subQuery->where('user_id', $user_id);
                });
            })->where('status', '1')->where('created_by', '!=', $user_id)->orderBy('created_at', 'desc')->count();
            return view('user.club.index', $data);
        } else {
            Toastr::warning(trans('To access this privilege, please upgrade your plan'), 'Warning', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }

    }

    public function clubDetails($id)
    {
        if (auth()->user()->role_id != 1) {
            abort(404, 'Page not found');
        }
        if (userPlanPrivilege() != false && userPlanPrivilege()->club != 1) {
            $user_id = Auth::user()->id;
            $data['club_id'] = $id;
            $data['title'] = 'Club';
            $data['row'] = Club::find($id);
            $data['member_count'] = ClubMember::where('club_id', $id)->where('status', '1')->count();
            $data['members'] = ClubMember::with('user', 'club')->where('club_id', $id)->where('status', '1')->paginate(10);
            $data['pending_members_count'] = ClubMember::with('user', 'club')->where('club_id', $id)->where('status', '0')->count();
            $data['flag'] = 0;
            $member_status = ClubMember::where('club_id', $id)->where('user_id', $user_id)->first();
            if ($member_status) {

                if ($member_status->status == 1) {
                    // Active member
                    $data['flag'] = 2;
                } else {
                    // Pending member
                    $data['flag'] = 3;
                }
            } else {
                // User is not a member of the club
                $data['flag'] = 1;
            }

            if (isset($data['row']) && $data['row']->status == '1') {
                return view('user.club.single_club', $data);
            } else {
                abort(404, 'Page not found');
            }

        } else {
            abort(404, 'Page not found');
        }

    }

    public function clubMembers($id)
    {
        if (auth()->user()->role_id != 1) {
            abort(404, 'Page not found');
        }
        if (userPlanPrivilege() != false && userPlanPrivilege()->club != 1) {
            $user_id = Auth::user()->id;
            $data['club_id'] = $id;
            $data['title'] = 'Club';
            $data['row'] = Club::find($id);
            $data['member_count'] = ClubMember::where('club_id', $id)->where('status', '1')->count();
            $data['members'] = ClubMember::with('user', 'club')->where('club_id', $id)->where('status', '1')->paginate(10);
            $data['pending_members'] = ClubMember::with('user', 'club')->where('club_id', $id)->where('status', '0')->get();
            $data['pending_members_count'] = ClubMember::with('user', 'club')->where('club_id', $id)->where('status', '0')->count();
            $data['flag'] = 0;
            $member_status = ClubMember::where('club_id', $id)->where('user_id', $user_id)->first();
            if ($member_status) {

                if ($member_status->status == 1) {
                    // Active member
                    $data['flag'] = 2;
                } else {
                    // Pending member
                    $data['flag'] = 3;
                }
            } else {
                // User is not a member of the club
                $data['flag'] = 1;
            }

            if (isset($data['row']) && $data['row']->status == '1') {
                return view('user.club.club_members', $data);
            } else {
                abort(404, 'Page not found');
            }

        } else {
            abort(404, 'Page not found');
        }

    }

    public function clubSettings($id)
    {
        if (auth()->user()->role_id != 1) {
            abort(404, 'Page not found');
        }
        if (userPlanPrivilege() != false && userPlanPrivilege()->club != 1) {
            $user_id = Auth::user()->id;
            $data['club_id'] = $id;
            $data['title'] = 'Club';
            $data['row'] = Club::find($id);
            $data['member_count'] = ClubMember::where('club_id', $id)->where('status', '1')->count();
            $data['members'] = ClubMember::with('user', 'club')->where('club_id', $id)->where('status', '1')->get();
            $data['pending_members_count'] = ClubMember::with('user', 'club')->where('club_id', $id)->where('status', '0')->count();
            $data['flag'] = 0;
            $member_status = ClubMember::where('club_id', $id)->where('user_id', $user_id)->first();
            if ($member_status) {

                if ($member_status->status == 1) {
                    // Active member
                    $data['flag'] = 2;
                } else {
                    // Pending member
                    $data['flag'] = 3;
                }
            } else {
                // User is not a member of the club
                $data['flag'] = 1;
            }

            if (isset($data['row']) && $data['row']->status == '1') {
                return view('user.club.club_settings', $data);
            } else {
                abort(404, 'Page not found');
            }

        } else {
            abort(404, 'Page not found');
        }
    }

    public function clubPosts($id)
    {
        if (auth()->user()->role_id != 1) {
            abort(404, 'Page not found');
        }
        if (userPlanPrivilege() != false && userPlanPrivilege()->club != 1) {
            $user_id = Auth::user()->id;
            $data['club_id'] = $id;
            $data['title'] = 'Club';
            $data['row'] = Club::find($id);
            $data['member_count'] = ClubMember::where('club_id', $id)->where('status', '1')->count();
            $data['members'] = ClubMember::with('user', 'club')->where('club_id', $id)->where('status', '1')->paginate(10);
            $data['posts'] = ClubPost::with('user', 'club')->where('club_id', $id)->where('status', '1')->withCount('comments')->paginate(8);
            $data['pending_members_count'] = ClubMember::with('user', 'club')->where('club_id', $id)->where('status', '0')->count();
            $data['flag'] = 0;
            $member_status = ClubMember::where('club_id', $id)->where('user_id', $user_id)->first();
            if ($member_status) {

                if ($member_status->status == 1) {
                    // Active member
                    $data['flag'] = 2;
                } else {
                    // Pending member
                    $data['flag'] = 3;
                }
            } else {
                // User is not a member of the club
                $data['flag'] = 1;
            }

            if (isset($data['row']) && $data['row']->status == '1') {
                return view('user.club.club_discussion', $data);
            } else {
                abort(404, 'Page not found');
            }

        } else {
            abort(404, 'Page not found');
        }
    }


    public function joinclub(Request $request)
    {
        if (userPlanPrivilege() != false && userPlanPrivilege()->club != 1) {
            DB::beginTransaction();
            try {

                $club = new ClubMember();
                $club->club_id = $request->club_id;
                $club->user_id = $request->user_id;
                $club->status = 0;
                $club->created_by = Auth::user()->id;
                $club->save();
                $title = Auth::user()->name . ' is want to join your club.';
                $this->saveUserNotification($title, 'user.club.index', 'club', 'user', $club->club->user_id);
                $this->sendUserPushNotification([$club->club->user_id], 'Club join request', $title, 'user.club.index');

            } catch (\Exception $e) {
                DB::rollback();
                Toastr::error(trans('An error occurred while processing your club join request. Please try again.'), 'Error', ["positionClass" => "toast-top-right"]);
                return redirect()->route('user.club.joinclub', $request->club_id);
            }
            DB::commit();
            Toastr::success(trans('Your join request has been successfully sent to the Club Owner. Please wait for approval.'), 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->route('user.club.joinclub', $request->club_id);
        } else {
            abort(404, 'Page not found');
        }
    }

    public function askQuestion($id)
    {
        if (auth()->user()->role_id != 1) {
            abort(404, 'Page not found');
        }
        if (userPlanPrivilege() != false && userPlanPrivilege()->club != 1) {
            $data['title'] = 'Club';
            $data['club_id'] = $id;
            return view('user.club.ask_question', $data);
        } else {
            abort(404, 'Page not found');
        }
    }

    public function question($id)
    {
        if (auth()->user()->role_id != 1) {
            abort(404, 'Page not found');
        }
        if (userPlanPrivilege() != false && userPlanPrivilege()->club != 1) {
            $data['title'] = 'Club';
            $data['row'] = ClubPost::with('club')->find($id);
            $data['comments'] = ClubComment::with('user')->where('club_post_id', $id)->get();
            if ($data['row'] && $data['row']->club && $data['row']->club->status == '1') {
                return view('user.club.question', $data);
            } else {
                abort(404, 'Page not found');
            }
        } else {
            abort(404, 'Page not found');
        }

    }

    public function reply(Request $request)
    {
        if (userPlanPrivilege() != false && userPlanPrivilege()->club != 1) {
            DB::beginTransaction();
            // dd($request->all());
            $request->validate([
                'msg' => 'required',
            ], [
                'msg.required' => 'The Comment is required',
            ]);

            try {

                $post_comment = new ClubComment();
                $post_comment->user_id = Auth::user()->id;
                $post_comment->club_post_id = $request->club_post_id;
                $post_comment->comments = $request->msg;
                $post_comment->created_by = Auth::user()->id;
                $post_comment->status = 1;
                $post_comment->save();

            } catch (\Exception $e) {
                dd($e);
                DB::rollback();
                Toastr::error(trans('Your comment not created !'), 'Error', ["positionClass" => "toast-top-right"]);
                return redirect()->back();
            }
            DB::commit();
            Toastr::success(trans('Your comment is submmited!'), 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        } else {
            abort(404, 'Page not found');
        }
    }

    public function replyDelete($id)
    {
        DB::beginTransaction();
        try {

            $comment =  ClubComment::find($id);
            $comment->delete();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('An error occurred while deleting the comment'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        DB::commit();
        Toastr::success(trans('Your comment deleted successfully'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();

    }
    public function replyUpdate($id, Request $request)
    {
        DB::beginTransaction();
        try {

            $comment =  ClubComment::find($id);
            if(isset($request->msg) && !empty($request->msg))
            {
                $comment->comments = $request->msg;
            }
            $comment->save();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('An error occurred while updating the comment'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        DB::commit();
        Toastr::success(trans('Your comment updated successfully'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();

    }
    public function create()
    {
        // dd(userPlanPrivilege());
        if (auth()->user()->role_id != 1) {
            abort(404, 'Page not found');
        }

        if (userPlanPrivilege() != false && userPlanPrivilege()->club != 1) {
            $forumPrivilege = userPlanPrivilege()->club ?? 0;
            // if($forumPrivilege == 3)
            if ($forumPrivilege) {
                $forumPrivilege = userPlanPrivilege()->club ?? 0;
                if ($forumPrivilege == 3) {
                    $data['title'] = 'Club';
                    return view('user.club.create', $data);
                } else {
                    Toastr::warning(trans('To access this privilege, please upgrade your plan'), 'Warning', ["positionClass" => "toast-top-right"]);
                    return redirect()->back();
                }

            } else {
                abort(404, 'Page not found');
            }

        } else {
            abort(404, 'Page not found');
        }

    }

    public function store(Request $request)
    {
        if (userPlanPrivilege() != false && userPlanPrivilege()->club != 1 && auth()->user()->currentUserPlan->package_id == 3) {
            DB::beginTransaction();
            // dd($request->all());
            $request->validate([
                'title' => 'required',
                'profile_photo' => 'required',
            ], [
                'title.required' => 'The club name is required',
                'profile_photo.required' => 'The profile photo is required',
            ]);

            try {

                if ($request->profile_photo) {
                    $profile = $request->file('profile_photo');
                    $base_name = preg_replace('/\..+$/', '', $profile->getClientOriginalName());
                    $base_name = explode(' ', $base_name);
                    $base_name = implode('-', $base_name);
                    $base_name = Str::lower($base_name);
                    $photo_name = $base_name . "-" . uniqid() . "." . $profile->getClientOriginalExtension();
                    $photo_path = 'uploads/club/profile';
                    $profile_photo = $photo_path . '/' . $photo_name;
                    $profile->move(public_path($photo_path), $photo_name);
                }
                if ($request->cover_photo) {
                    $cover = $request->file('cover_photo');
                    $base_name = preg_replace('/\..+$/', '', $cover->getClientOriginalName());
                    $base_name = explode(' ', $base_name);
                    $base_name = implode('-', $base_name);
                    $base_name = Str::lower($base_name);
                    $photo_name = $base_name . "-" . uniqid() . "." . $cover->getClientOriginalExtension();
                    $photo_path = 'uploads/club/cover';
                    $cover_photo = $photo_path . '/' . $photo_name;
                    $cover->move(public_path($photo_path), $photo_name);
                }
                $club = new Club();
                $club->title = $request->title;
                $club->covar_photo = $cover_photo;
                $club->profile_photo = $profile_photo;
                $club->short_description = $request->short_description;
                $club->about_club = $request->about;
                $club->rules_club = $request->rules;
                // $club->created_by             = $request->user_id;
                $club->user_id = Auth::user()->id;
                $club->status = 0;
                $result = $club->save();

                if ($result) {
                    $clubMember = new ClubMember();
                    $clubMember->club_id = $club->id;
                    $clubMember->user_id = Auth::user()->id;
                    $clubMember->status = 1;
                    $clubMember->created_by = Auth::user()->id;
                    $clubMember->save();

                    $name = Auth::user()->name . ' ' . Auth::user()->last_name;
                    $data = [
                        'admin_email' => getSetting()->support_email,
                        'template' => 'clubemail',
                        'subject' => 'New Club Created!',
                        'greeting' => 'Hello, Admin,',
                        'body' => 'A new club has been created from user - ' . $name . '. Please review and respond to the users club as soon as possible.',
                        'title' => 'Club Name: ' . $club->title,
                        'link' => route('admin.club.index'),
                        'msg' => 'Click here to navigate to the club page',
                        'thanks' => 'Thank you and stay with ' . ' ' . config('app.name'),
                        'site_url' => route('home'),
                        'site_name' => config('app.name'),
                        'copyright' => ' © ' . ' ' . Carbon::now()->format('Y') . config('app.name') . ' ' . 'All rights reserved.',
                        'footer' => '0',
                    ];
                    // if ($settings->app_mode == 'live') {
                    Mail::to($data['admin_email'])->send(new Allmail($data));
                    // }
                }

            } catch (\Exception $e) {
                dd($e);
                DB::rollback();
                Toastr::error(trans('Club not Created!'), 'Error', ["positionClass" => "toast-top-right"]);
                return redirect()->route('user.club.create');
            }
            DB::commit();
            Toastr::success(trans('Club Created Successfully. Please wait for Admin approval.'), 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->route('user.club.index');
        } else {
            abort(404, 'Page not found');
        }
    }

    public function update($id, Request $request)
    {
        if (userPlanPrivilege() != false && userPlanPrivilege()->club != 1) {
            DB::beginTransaction();
            // dd($request->all());

            try {
                $club = Club::find($id);
                if ($request->profile_photo) {
                    $profile = $request->file('profile_photo');
                    $base_name = preg_replace('/\..+$/', '', $profile->getClientOriginalName());
                    $base_name = explode(' ', $base_name);
                    $base_name = implode('-', $base_name);
                    $base_name = Str::lower($base_name);
                    $photo_name = $base_name . "-" . uniqid() . "." . $profile->getClientOriginalExtension();
                    $photo_path = 'uploads/club/profile';
                    $profile->move(public_path($photo_path), $photo_name);
                    $club->profile_photo = $photo_path . '/' . $photo_name;
                }

                if ($request->cover_photo) {
                    $cover = $request->file('cover_photo');
                    $base_name = preg_replace('/\..+$/', '', $cover->getClientOriginalName());
                    $base_name = explode(' ', $base_name);
                    $base_name = implode('-', $base_name);
                    $base_name = Str::lower($base_name);
                    $photo_name = $base_name . "-" . uniqid() . "." . $cover->getClientOriginalExtension();
                    $photo_path = 'uploads/club/cover';
                    $cover->move(public_path($photo_path), $photo_name);
                    $club->covar_photo = $photo_path . '/' . $photo_name;
                }

                $club->title = $request->title;
                $club->short_description = $request->short_description;
                $club->about_club = $request->about;
                $club->rules_club = $request->rules;
                $result = $club->save();

            } catch (\Exception $e) {
                DB::rollback();
                Toastr::error(trans('Club not Updated!'), 'Error', ["positionClass" => "toast-top-right"]);
                return redirect()->back();
            }
            DB::commit();
            Toastr::success(trans('Club Successfully Updated!'), 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        } else {
            abort(404, 'Page not found');
        }
    }

    public function changeStatus($id, $status)
    {
        if (userPlanPrivilege() != false && userPlanPrivilege()->club != 1) {
            DB::beginTransaction();
            try {
                $clubmember = ClubMember::find($id);
                if ($status == 1) {
                    $clubmember->status = $status;
                    $result = $clubmember->save();
                    $msg = 'Membership Request Approve';

                    if ($result) {
                        $name = $clubmember->user->name . ' ' . $clubmember->user->last_name;
                        $data = [
                            'user_email' => $clubmember->user->email,
                            'template' => 'clubemail',
                            'title' => '0',
                            'subject' => 'Club Membership Request Approved',
                            'greeting' => 'Hi ' . $name . ',',
                            'link' => '0',
                            'body' => 'Your join request for the club - "' . $clubmember->club->title . '" has been approved by the club owner.',
                            'thanks' => 'Thank you and stay with ' . ' ' . config('app.name'),
                            'site_url' => route('home'),
                            'site_name' => config('app.name'),
                            'copyright' => ' © ' . ' ' . Carbon::now()->format('Y') . config('app.name') . ' ' . 'All rights reserved.',
                            'footer' => '0',
                        ];
                        // if ($settings->app_mode == 'live') {
                        Mail::to($data['user_email'])->send(new Allmail($data));
                        // }
                    }
                } else {
                    $clubmember->delete();
                    $msg = 'Membership Request denied';
                }
                $message = $status == 1 ? 'approved' : 'declined';
                $title = 'Your join request to the club (' . $clubmember->club->title . ') has been ' . $message;
                $routeString = 'user.club.joinclub,' . $clubmember->club->id;
                $this->saveUserNotification($title, $routeString, 'club', 'user', $clubmember->user_id);
                $this->sendUserPushNotification([$clubmember->user_id], 'Club join', $title, $routeString);


            } catch (\Exception $e) {
                dd($e);
                DB::rollback();
                Toastr::error(trans('An error occurred while processing the membership request'), 'Error', ["positionClass" => "toast-top-right"]);
                return redirect()->back();
            }
            DB::commit();
            Toastr::success(trans($msg), 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        } else {
            abort(404, 'Page not found');
        }
    }

    public function leave(Request $request)
    {
        if (userPlanPrivilege() && userPlanPrivilege()->club != 1) {
            $ClubMember = ClubMember::where('club_id', $request->club_id)
                ->where('user_id', $request->user_id)->first();
            $ClubMember->delete();
            if (request()->has('join_request') && $request->join_request == '1') {
                Toastr::success('Your join request cancelled successfully');
            } else {
                Toastr::success('You have successfully left the club');
            }
            return redirect()->back();
        } else {
            abort(404, 'Page not found');
        }
    }

    public function submitQuestion(Request $request)
    {
        if (userPlanPrivilege() && userPlanPrivilege()->club != 1) {
            DB::beginTransaction();
            // dd($request->all());
            $request->validate([
                'title' => 'required',
                'msg' => 'required',
            ], [
                'title.required' => 'The question title is required',
                'msg.required' => 'The description is required',
            ]);

            try {

                $slug = Str::slug($request->title);
                $check_slug = ClubPost::where('slug', $slug)->first();

                if ($check_slug) {
                    $uniqueId = Str::uuid()->toString();
                    $slug = $slug . '-' . $uniqueId;
                }

                $club_post = new ClubPost();
                $club_post->club_id = $request->club_id;
                $club_post->title = $request->title;
                $club_post->slug = $slug;
                $club_post->descriptions = $request->msg;
                if ($request->attachment) {
                    $attachment = $request->file('attachment');
                    $base_name = preg_replace('/\..+$/', '', $attachment->getClientOriginalName());
                    $base_name = explode(' ', $base_name);
                    $base_name = implode('-', $base_name);
                    $base_name = Str::lower($base_name);
                    $attachment_name = $base_name . "-" . uniqid() . "." . $attachment->getClientOriginalExtension();
                    $attachment_path = 'uploads/club/posts';
                    $attachment->move(public_path($attachment_path), $attachment_name);
                    $attachment_url = $attachment_path . '/' . $attachment_name;
                    $club_post->image = $attachment_url;
                }
                $club_post->created_by = Auth::user()->id;
                $club_post->status = 1;
                $club_post->save();
                // $club_members = ClubMember::where('status', 1)->where('user_id', '!=', Auth::user()->id)
                //                 ->where('club_id', $request->club_id)->get();
                $title = Auth::user()->name . ' post on your club ' . $club_post->club->title;
                $routeString = 'user.club.clubPosts,' . $request->club_id;
                // foreach ($club_members as $member) {
                $this->saveUserNotification($title, $routeString, 'club', 'user', $club_post->club->user_id);
                $this->sendUserPushNotification([$club_post->club->user_id], 'Club post', $title, $routeString);
                // }

            } catch (\Exception $e) {
                DB::rollback();
                Toastr::error(trans('Your question not created !'), 'Error', ["positionClass" => "toast-top-right"]);
                return redirect()->route('user.club.clubPosts', $request->club_id);
            }
            DB::commit();
            Toastr::success(trans('Your question created successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
            return redirect()->route('user.club.clubPosts', $request->club_id);
        } else {
            abort(404, 'Page not found');
        }
    }

}
