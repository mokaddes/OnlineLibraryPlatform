<?php
namespace App\Traits;
use App\Models\Notification as WebNotification;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Log;

trait Notification {
    /**
     * @param $title
     * @param $action
     * @param $type
     * @param string $notify
     * @param null $user_id
     * @return WebNotification
     */
    public function saveUserNotification($title, $action, $type , string $notify = 'user', $user_id = null): WebNotification
    {
        $notification = new WebNotification();
        $notification->title = $title;
        $notification->user_id = $user_id;
        $notification->action_route = $action;
        $notification->type = $type;
        $notification->notify_for = $notify;
        $notification->save();
        return $notification;
    }

    /**
     * @param $title
     * @param $action
     * @param $admin_id
     * @param $type
     * @return WebNotification
     */
    public function saveAdminNotification($title, $action, $type = null, $admin_id = null): WebNotification
    {
        $notification = new WebNotification();
        $notification->title = $title;
        $notification->admin_id = $admin_id;
        $notification->action_route = $action;
        $notification->type = $type;
        $notification->notify_for = 'admin';
        $notification->save();
        return $notification;
    }

    /**
     * @param $id
     * @return void
     */
    public function is_read($id)
    {
        $notification =  WebNotification::findOrFail($id);
        $notification->is_read = 1;
        $notification->save();
    }

    public function sendPushNotification($encodedData)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = [
            'Authorization: key=' . env('FIREBASE_SERVER_KEY'),
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        $notification_res = json_decode($result, TRUE);
        return $notification_res;
    }


    public function sendUserPushNotification($user_id,$title,$body,$action)
    {

        $notification_res = [];

        try {
            $settings = Setting::first();
            $devices = User::whereIn('id', $user_id)
                ->whereNotNull('device_id')
                ->pluck('device_id')
                ->all();
            if ($devices) {
                $data = [
                    "registration_ids" => $devices,
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                        'image' => null,
                        'icon' => asset($settings->favicon_image),
                    ],
                    'priority' => 'high',
                ];
                $encodedData = json_encode($data);
                $notification_res = $this->sendPushNotification($encodedData);
            }

        } catch (\Exception $e) {
            Log::alert($e->getMessage());
        }
        return $notification_res;
    }
}
