<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushNotification;
use App\Models\User;
use App\Traits\Notification;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PushNotificationController extends Controller
{
    use Notification;

    public function index()
    {
        $data['title'] = 'Push Notification';
        $data['notifications'] = PushNotification::latest()->get();
        return view('admin.push.index', $data);
    }

    public function create()
    {
        $data['title'] = 'Push Notification Create';
        $data['users'] = User::select('id', 'name', 'last_name', 'role_id', 'device_id')->where('status', 1)->whereNotNull('device_id')->orderBy('role_id', 'asc')->orderBy('name', 'asc')->get();
        $data['types'] = [
            1 => 'Reader',
            2 => 'Author',
            3 => 'Institute',
        ];
        return view('admin.push.create', $data);
    }

    public function edit($id)
    {
        $data['title'] = 'Push Notification Update';
        $data['push'] = PushNotification::findOrFail($id);
        $data['users'] = User::select('id', 'name', 'last_name', 'role_id', 'device_id')->where('status', 1)->whereNotNull('device_id')->orderBy('role_id', 'asc')->orderBy('name', 'asc')->get();
        $data['types'] = [
            1 => 'Reader',
            2 => 'Author',
            3 => 'Institute',
        ];
        return view('admin.push.create', $data);
    }
    public function view($id)
    {
        $data['title'] = 'Push Notification view';
        $data['push'] = PushNotification::findOrFail($id);
        $data['users'] = User::select('id', 'name', 'last_name', 'role_id', 'device_id')->orderBy('role_id', 'asc')->orderBy('name', 'asc')->findMany($data['push']->user_ids);
        $data['types'] = [
            1 => 'Reader',
            2 => 'Author',
            3 => 'Institute',
        ];
        return view('admin.push.view', $data);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $push = new PushNotification();
            $this->savedPushNotification($request, $push);
            DB::commit();
            Toastr::success('Push notification successfully saved.', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Something went wrong!', 'Error');
            DB::rollBack();
        }
        return redirect()->route('admin.push-notification.index');
    }

    /**
     * @param Request $request
     * @param $push
     * @return void
     */
    public function savedPushNotification(Request $request, $push): void
    {
        $push->title = $request->title;
        $push->body = $request->body;
        $push->user_ids = $request->user_ids;
        $push->status = $request->status;
        $push->url = $request->url;
        $push->created_by = Auth::id();
        $push->save();
    }

    public function update(Request $request, $id)
    {
        $push = PushNotification::findOrFail($id);
        try {
            DB::beginTransaction();
            $this->savedPushNotification($request, $push);
            DB::commit();
            Toastr::success('Push notification successfully updated.', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Something went wrong!', 'Error');
            DB::rollBack();
        }
        return redirect()->route('admin.push-notification.index');
    }

    public function delete($id)
    {
        $push = PushNotification::findOrFail($id);
        try {
            DB::beginTransaction();
            $push->delete();
            DB::commit();
            Toastr::success('Push notification successfully deleted.', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Something went wrong!', 'Error');
            DB::rollBack();
        }
        return redirect()->route('admin.push-notification.index');
    }

    public function send($id)
    {
        $push = PushNotification::findOrFail($id);
        try {
            if ($push->status === 1) {
                $notification_res = $this->sendUserPushNotification($push->user_ids, $push->title, $push->body, $push->url);
                if ($notification_res) {
                    $push->total_success = $notification_res['success'];
                    $push->total_send = $push->total_send + 1;
                    $push->save();
                    Toastr::success('Push notification successfully sent.', 'Success');
                } else {
                    Toastr::error('Push notification send failed.', 'Error');
                }
            } else {
                Toastr::error('Push notification is not active.', 'Error');
            }
        } catch (\Exception $e) {
            Toastr::error('Something went wrong!', 'Error');
        }
        return redirect()->route('admin.push-notification.index');
    }
}
