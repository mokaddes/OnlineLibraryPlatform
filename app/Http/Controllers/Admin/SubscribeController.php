<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DrewM\MailChimp\MailChimp;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function index()
    {
        $MailChimp = new MailChimp($setting->mailchimp_api_key ?? 'adf79b432fee63ba2dde5aaedde3ef20-us8');
        $list_id = $setting->mailchimp_list_id ?? '98d4cf1012';
        $result = $MailChimp->get('lists/' . $list_id . '/members');
        $members = $result['members'];
        dd($members);
    }

    public function unsubscribe($email)
    {

    }
}
