<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\ClubMember;
use App\Models\User;
use App\Traits\Notification;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClubController extends Controller
{
    use Notification;

    public $user;
    protected $club;

    public function __construct(Club $club)
    {
        $this->club = $club;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    public function index()
    {
        if (is_null($this->user) || !$this->user->can('admin.club.index')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }
        $data['title'] = 'Clubs';
        $data['rows'] = Club::latest()->get();
        return view('admin.club.index', compact('data'));
    }

    public function create()
    {
        if (is_null($this->user) || !$this->user->can('admin.club.create')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }
        $data['title'] = 'Clubs';
        $data['users'] = User::where('status', '1')->where('role_id', '1')->get();
        return view('admin.club.create', compact('data'));
    }


    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.club.store')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        DB::beginTransaction();
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'owner' => 'required',
            'profile_photo' => 'required',
        ], [
            'title.required' => 'The club name is required',
            'owner.required' => 'The club owner is required',
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
            // $club->created_by             = Auth::user()->id;
            $club->admin_id = Auth::user()->id;
            $club->user_id = $request->owner;
            $club->status = 1;
            $result = $club->save();

            if ($result) {
                $clubMember = new ClubMember();
                $clubMember->club_id = $club->id;
                $clubMember->user_id = $request->owner;
                $clubMember->status = 1;
                $clubMember->created_by = $request->owner;
                $clubMember->save();
            }
            $title = 'An admin create a club (' . $club->title . ') for you ';
            $this->saveUserNotification($title, 'user.club.index', 'club', 'user', $request->owner);
            $this->sendUserPushNotification([$request->owner], 'Club Created', $title, route('user.club.index'));


        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Club not Created !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.club.create');
        }
        DB::commit();
        Toastr::success(trans('Club Created Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.club.index');
    }

    public function changeStatus($id, $status)
    {
        if (is_null($this->user) || !$this->user->can('admin.club.status.change')) {
            abort(403, 'Sorry !! You are Unauthorized.');
        }

        DB::beginTransaction();
        try {
            $club = Club::find($id);
            $club->status = $status;
            $club->save();

            $message = $status == 1 ? 'approved' : 'Disabled';
            $title = 'Your club (' . $club->title . ') has been ' . $message;
            $msg = 'Club' . $message;
            if ($club->user_id != '') {
                $this->saveUserNotification($title, 'user.club.index', 'club', 'user', $club->user_id);
                $this->sendUserPushNotification([$club->user_id], $msg, $title, route('user.club.index'));
            }


        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Club Status Not Updated!'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->route('admin.club.index');
        }
        DB::commit();
        Toastr::success(trans('Club Status Updated!'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->route('admin.club.index');
    }

    public function edit($id)
    {
        $data['title'] = 'Clubs';
        return view('admin.club.edit', compact('data'));
    }


    public function update(Request $request)
    {
        // if (is_null($this->user) || !$this->user->can('admin.edit')) {
        //     abort(403, 'Sorry !! You are Unauthorized to update users.');
        // }


    }

    // View Club
    public function view(Request $request, $id)
    {
        $data['club_id'] = $id;
        $data['title'] = 'Club';
        $data['row'] = Club::find($id);
        $data['member_count'] = ClubMember::where('club_id', $id)->where('status', '1')->count();
        $data['members'] = ClubMember::with('user', 'club')->where('club_id', $id)->where('status', '1')->paginate(10);
        return view('admin.club.single_club', $data);
    }
    public function members($id)
    {

            $data['club_id'] = $id;
            $data['title'] = 'Club';
            $data['row'] = Club::find($id);
            $data['member_count'] = ClubMember::where('club_id', $id)->where('status', '1')->count();
            $data['members'] = ClubMember::with('user', 'club')->where('club_id', $id)->where('status', '1')->paginate(10);
            return view('admin.club.club_members', $data);
    }
    public function delete($id)
    {
        // if (is_null($this->user) || !$this->user->can('admin.category.delete')) {
        //     abort(403, 'Sorry !! You are Unauthorized.');
        // }

    }
    // public function clubAbout(){
    //     $data['title'] = 'Club';
    //     return view('admin.club.about_club', compact('data'));
    // }

    // public function clubCommunity(){
    //     $data['title'] = 'Club Community';
    //     return view('admin.club.club_community', compact('data'));
    // }

    // public function clubDetails(){
    //     $data['title'] = 'Questions';
    //     return view('admin.club.club_details', compact('data'));
    // }

    // public function addQuestion(){
    //     $data['title'] = 'Add Questions';
    //     return view('admin.club.add_question', compact('data'));
    // }


}
