<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification as WebNotification;
use App\Traits\RepoResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class NotificationController extends Controller
{
    use RepoResponse;

    public function getNotification()
    {
        $user = JWTAuth::user();
        $notifications = WebNotification::where('notify_for', 'user')->where('user_id', $user->id)->latest()->get();
        $notifications->map(function ($notification) {
            $routeString = $notification->action_route;

            $routeParts = explode(',', $routeString, 2);

            $routeName = $routeParts[0] ?? null;
            $parameter = $routeParts[1] ?? null;

            if (isset($parameter)) {
                $notification->url = route($routeName, $parameter);
            } else {
                $notification->url = route($routeName);
            }

            return $notification;
        });
        $data = [
            'all_notification' => $notifications,
            'all_count' => $notifications->count(),
            'unread_notifications' => WebNotification::where('user_id', $user->id)->where('is_read', 0)->latest()->get(),
            'unread_count' => $notifications->where('is_read', 0)->count(),

        ];
        return $this->apiResponse('1', 200, 'Notifications are successfully fetched.', '', $data);
    }

    public function readNotification(Request $request)
    {
        $notifyId = $request->notification_id;
        $userId = Auth::user()->id;
        if ($notifyId == 'all') {
            \App\Models\Notification::where('user_id', $userId)->update(['is_read' => 1]);
        } elseif ($notifyId == 'admin'){
            \App\Models\Notification::where('notify_for', 'admin')->update(['is_read' => 1]);
        }else {
            \App\Models\Notification::where('id', $notifyId)->update(['is_read' => 1]);
        }
        $newCount = \App\Models\Notification::where('user_id', $userId)->where('is_read', 0)->count();
        $data = [
            'unreadCount' => $newCount
        ];

        return $this->apiResponse('1', 200, 'Notifications are in read list now.', '', $data);
    }
}
