<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\ClubComment;
use App\Models\ClubMember;
use App\Models\ClubPost;
use App\Models\User;
use App\Traits\Notification;
use App\Traits\RepoResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClubController extends Controller
{

    use RepoResponse, Notification;

    /**
     * @var User|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $user;

    public function __construct()
    {
        $this->user = Auth::guard('api')->user();
    }

    public function index(Request $request)
    {
        $user_id = $this->user->id;
        $data['all_clubs'] = Club::withCount(['members' => function ($query) {
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
            ->get();

        $data['my_clubs'] = Club::withCount(['members' => function ($query) {
            $query->where('status', 1);
        }])
            ->where('status', '1')
            ->whereHas('members', function ($query) use ($user_id) {
                $query->where('user_id', $user_id)->where('status', '1');
            })
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($club) use ($user_id) {
                $club->is_owner = $club->user_id == $user_id;
                return $club;
            });

        $data['posts'] = ClubPost::with(['user', 'club'])->whereHas('club', function ($query) use ($user_id) {
            $query->whereHas('members', function ($subQuery) use ($user_id) {
                $subQuery->where('user_id', $user_id);
            });
        })->where('status', '1')->where('created_by', '!=', $user_id)->orderBy('created_at', 'desc')->get()->take(8);


        $data['posts_count'] = ClubPost::with('user')->whereHas('club', function ($query) use ($user_id) {
            $query->whereHas('members', function ($subQuery) use ($user_id) {
                $subQuery->where('user_id', $user_id);
            });
        })->where('status', '1')->where('created_by', '!=', $user_id)->orderBy('created_at', 'desc')->count();

        return $this->apiResponse('1', 200, 'Data successfully found', '', $data);
    }

    public function clubDetails($id)
    {
        $user_id = $this->user->id;
        $data['club'] = Club::find($id);
        $data['members'] = ClubMember::with('user')->where('club_id', $id)->where('status', '1')->get();
        $data['member_count'] = $data['members']->count();
        $data['pending_members'] = ClubMember::with('user')->where('club_id', $id)->where('status', '0')->get();
        $data['pending_members_count'] = $data['pending_members']->count();
        $data['posts'] = ClubPost::with('user', 'club')->where('club_id', $id)->where('status', '1')->withCount('comments')->get();

        $member_status = ClubMember::where('club_id', $id)->where('user_id', $user_id)->first();
        $data['is_owner'] = $data['club']->user_id == $user_id;
        if ($member_status) {
            if ($member_status->status == 1) {
                $data['flag'] = 2;
                $data['flag_message'] = 'You are an active member of this club.';
            } else {
                $data['flag'] = 3;
                $data['flag_message'] = 'You are not an active member of this club';
            }
        } else {
            $data['flag'] = 1;
            $data['flag_message'] = 'You are not a member of this club';
        }
        return $this->apiResponse('1', 200, 'Data successfully found', '', $data);
    }


    public function joinClub(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'club_id' => 'required|exists:clubs,id',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse('0', 400, $validator->errors()->first(), [], $validator->errors());
        }

        DB::beginTransaction();
        try {

            $club = new ClubMember();
            $club->club_id = $request->club_id;
            $club->user_id = $this->user->id;
            $club->status = 0;
            $club->created_by = $this->user->id;
            $club->save();

            $title = $this->user->name . ' is want to join your club.';
            $this->saveUserNotification($title, 'user.club.index', 'club', 'user', $club->club->user_id);
            $this->sendUserPushNotification([$club->club->user_id], 'Join request', $title, route('user.club.index'));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->apiResponse('0', 400, 'Something is wrong', [], $e->getMessage());
        }
        DB::commit();
        return $this->apiResponse('1', 200, 'Your join request has been successfully sent to the Club Owner. Please wait for approval.', '', $club);
    }


    public function question($id)
    {
        $post = ClubPost::with(['club', 'user', 'comments' => function ($q) {
            $q->with('user');
        }])->find($id);
        if (!$post) {
            return $this->apiResponse('0', 400, 'Post not found',);
        }
        $post->setRelation('postUser', $post->user);
        unset($post->user);
        return $this->apiResponse('1', 200, 'Data successfully found', '', $post);
    }

    public function reply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'club_post_id' => 'required|exists:club_posts,id',
            'comments' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse('0', 400, $validator->errors()->first(), [], $validator->errors());
        }


        DB::beginTransaction();

        try {

            $post_comment = new ClubComment();
            $post_comment->user_id = $this->user->id;
            $post_comment->club_post_id = $request->club_post_id;
            $post_comment->comments = $request->comments;
            $post_comment->created_by = $this->user->id;
            $post_comment->status = 1;
            $post_comment->save();
        } catch (\Exception $e) {
            DB::rollback();
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse('0', 400, 'Something is wrong', $e->getMessage(), [], $errorArray);
        }
        DB::commit();
        return $this->apiResponse('1', 200, 'Your reply has been successfully saved.', '', $post_comment);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:250',
            'profile_photo' => 'required|image',
            'cover_photo' => 'nullable|image',
            'short_description' => 'nullable|string|max:1000',
            'about' => 'nullable|string|max:2000',
            'rules_club' => 'nullable|string|max:2000',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse('0', 422, $validator->errors()->first(), [], $validator->errors());
        }

        DB::beginTransaction();
        try {


            $club = new Club();
            $club->title = $request->title;
            $club->short_description = $request->short_description;
            $club->about_club = $request->about;
            $club->rules_club = $request->rules;
            $club->user_id = $this->user->id;
            $club->status = 0;

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
                $club->profile_photo = $profile_photo;
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
                $club->covar_photo = $cover_photo;
            }

            $result = $club->save();

            if ($result) {
                $clubMember = new ClubMember();
                $clubMember->club_id = $club->id;
                $clubMember->user_id = $this->user->id;
                $clubMember->status = 1;
                $clubMember->created_by = $this->user->id;
                $clubMember->save();
            }

            DB::commit();
            return $this->apiResponse('1', 200, 'Club successfully created', '', $club);
        } catch (\Exception $e) {
            DB::rollback();
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'status' => 'failed',
            ];

            return $this->apiResponse('1', 200, 'Something is wrong', $e->getMessage(), [], $errorArray);
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:250',
            'profile_photo' => 'nullable|image',
            'cover_photo' => 'nullable|image',
            'short_description' => 'nullable|string|max:1000',
            'about' => 'nullable|string|max:2000',
            'rules_club' => 'nullable|string|max:2000',

        ]);

        if ($validator->fails()) {
            return $this->apiResponse('0', 422, $validator->errors()->first(), [], $validator->errors());
        }

        DB::beginTransaction();

        try {
            $club = Club::find($id);
            if ($request->has('profile_photo') && !empty($request->profile_photo)) {
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

            if ($request->has('cover_photo') && !empty($request->cover_photo)) {
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
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse('1', 200, 'Something is wrong', $e->getMessage(), [], $errorArray);
        }
        DB::commit();
        return $this->apiResponse('1', 200, 'Club successfully updated', '', $club);
    }

    public function changeStatus($id, $status)
    {
        DB::beginTransaction();
        try {
            $clubMember = ClubMember::find($id);
            if (intval($clubMember->club->clubAdmin->id) !== intval($this->user->id)) {
                return $this->apiResponse('0', 422, 'You are not allowed to change the status of member', [], []);
            }
            if ($status == 1) {
                $clubMember->status = $status;
                $clubMember->save();
                $msg = 'Membership Request Approve';
            } else {
                $clubMember->delete();
                $msg = 'Membership Request denied';
            }

            $message = $status == 1 ? 'approved' : 'declined';
            $title = 'Your join request to the club (' . $clubMember->club->title . ') has been ' . $message;
            $routeString = 'user.club.joinclub,' . $clubMember->club->id;
            $this->saveUserNotification($title, $routeString, 'club', 'user', $clubMember->user_id);
            $this->sendUserPushNotification([$clubMember->user_id], 'join request', $title, $routeString);
        } catch (\Exception $e) {
            DB::rollback();
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse('1', 200, 'Something is wrong', $e->getMessage(), [], $errorArray);
        }
        DB::commit();
        return $this->apiResponse('1', 200, $msg, '', $clubMember);
    }

    public function leave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'club_id' => 'required|exists:clubs,id',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse('0', 400, $validator->errors()->first(), [], $validator->errors());
        }

        $member = ClubMember::where('club_id', $request->club_id)
            ->where('user_id', $this->user->id)->first();
        if (!$member) {
            return $this->apiResponse('1', 200, 'Your are not member of this club.', '');
        }
        if (intval($member->status) == 0) {
            $message = 'Your join request cancelled successfully';
        } else {
            $message = 'You have successfully left the club';
        }
        $member->delete();
        return $this->apiResponse('1', 200, $message, '');
    }

    public function submitQuestion(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:250',
            'descriptions' => 'required|max:2000',
            'club_id' => 'required|exists:clubs,id',
            'attachment' => 'nullable|file',

        ]);
        if ($validator->fails()) {
            return $this->apiResponse('0', 400, $validator->errors()->first(), [], $validator->errors());
        }


        DB::beginTransaction();

        try {

            $slug = Str::slug($request->title);
            $check_slug = ClubPost::where('slug', $slug)->first();
            if ($check_slug) {
                $slug = $slug . '_' . uniqid();
            }

            $club_post = new ClubPost();
            $club_post->club_id = $request->club_id;
            $club_post->title = $request->title;
            $club_post->slug = $slug;
            $club_post->descriptions = $request->descriptions;
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
            $club_post->created_by = $this->user->id;
            $club_post->status = 1;
            $club_post->save();
            $club_members = ClubMember::where('status', 1)->where('user_id', '!=', Auth::user()->id)->where('club_id', $request->club_id)->get();
            $title = Auth::user()->name . ' post on your club ' . $club_post->club->title;
            $routeString = 'user.club.joinclub,' . $request->club_id;
            foreach ($club_members as $member) {
                $this->saveUserNotification($title, $routeString, 'club', 'user', $member->user_id);
                $this->sendUserPushNotification([$member->user_id], 'Club Post', $title, $routeString);
            }
        } catch (\Exception $e) {
            DB::rollback();
            //            dd($e);
            $errorArray = [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ];
            return $this->apiResponse('1', 200, 'Something is wrong', $e->getMessage(), [], $errorArray);
        }
        DB::commit();
        return $this->apiResponse('1', 200, 'Question successfully submitted', '', $club_post);
    }
}
