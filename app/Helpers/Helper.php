<?php

use App\Models\BlogComment;
use App\Models\BorrowedBook;
use App\Models\ForumComment;
use App\Models\Notification;
use App\Models\Product;
use App\Models\ProductFavourite;
use App\Models\Report;
use App\Models\Setting;
use DrewM\MailChimp\MailChimp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FSRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Stevebauman\Location\Facades\Location;

if (!function_exists('getSetting')) {
    function getSetting()
    {
        return DB::table('settings')->orderBy('id', 'DESC')->first();
    }
}

if (!function_exists('array_wrap')) {
    function array_wrap($value)
    {
        return is_array($value) ? $value : [$value];
    }
}


if (!function_exists('applyPromo')) {
    function applyPromo()
    {
        return auth()->user()->role_id == 1 && (isset(auth()->user()->package->price) && auth()->user()->package->price == 0) || auth()->user()->currentUserPlan()->count() < 1;
    }
}




/*Print Validation Error List*/
if (!function_exists('vError')) {
    function vError($errors)
    {
        if ($errors->any()) {
            foreach ($errors->all() as $error) {
                echo '<li class="text-danger">' . $error . '</li>';
            }
        } else {
            echo 'Not found any validation error';
        }
    }
}
if (!function_exists('is_favorite')) {
    function is_favorite($id): bool
    {
        return $id && Auth::check() && ProductFavourite::where('product_id', $id)->where('user_id', Auth::user()->id)->exists();
    }
}
if (!function_exists('is_borrowed')) {
    function is_borrowed($id): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        $book = Product::find($id);
        if ($user->role_id == 1) {
            $isBorrowed = BorrowedBook::where('product_id', $id)
                ->where('user_id', $user->id)
                ->where('is_valid', '1')
                ->where(function ($q) {
                    $q->where('borrowed_enddate', '>=', now())
                        ->orWhere('is_bought', 1);
                })
                ->first();
        } elseif ($user->role_id == 3) {
            $isBorrowed = BorrowedBook::where('product_id', $id)->where('user_id', $user->id)->where('is_valid', '1')->where('is_institution', '1')->first();
        } else {
            $isBorrowed = $book->where('user_id', $user->id)->first();
        }
        return (bool)$isBorrowed;
    }
}
if (!function_exists('is_bought')) {
    function is_bought($id): bool
    {
        if (!Auth::check()) {
            return false;
        }
        return  BorrowedBook::where('product_id', $id)->where('user_id', Auth::user()->id)->where('is_valid', '1')->where('is_bought', '1')->exists();
    }
}

if (!function_exists('get_error_response')) {
    function get_error_response($code, $reason, $errors = [], $error_as_string = '', $description = '')
    {
        if ($error_as_string == '') {
            $error_as_string = $reason;
        }
        if ($description == '') {
            $description = $reason;
        }
        return [
            'code' => $code,
            'errors' => $errors,
            'error_as_string' => $error_as_string,
            'reason' => $reason,
            'description' => $description,
            'error_code' => $code,
            'link' => ''
        ];
    }
}
//word limit method
if (!function_exists('wordLimit')) {
    function wordLimit($text, $limit = 10, $suffix = '...')
    {
        $words = preg_split("/\s+/", $text, $limit + 1);
        if (count($words) > $limit) {
            array_pop($words);
            return implode(' ', $words) . $suffix;
        }
        return $text;
    }
}

if (!function_exists('checkPackageValidity')) {
    function checkPackageValidity($user_id)
    {
        $user = DB::table('users')->where('id', $user_id)->first();
        $today = strtotime("today midnight");
        $expire = strtotime($user->plan_validity);
        if ($today >= $expire) {
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('checkCardLimit')) {
    function checkCardLimit($user_id)
    {
        $user = DB::table('users')->where('id', $user_id)->first();
        if ($user->plan_details) {
            $plan_details = json_decode($user->plan_details, true);
            if ($plan_details['no_of_vcards'] != 9999) {
                $user_card = DB::table('business_cards')->where('status', 1)->where('user_id', $user_id)->count();
                if ($plan_details['no_of_vcards'] <= $user_card) {
                    return false;
                }
            }
        }
        return true;
    }
}

if (!function_exists('getPhoto')) {
    function getPhoto($path)
    {
        if ($path) {
            $ppath = public_path($path);
            if (file_exists($ppath)) {
                return asset($path);
            } else {
                return asset('assets/img/card/personal.png');
            }
        } else {
            return asset('assets/img/card/personal.png');
        }
    }
}
if (!function_exists('getUserLocation')) {
    function getUserLocation()
    {
        $ip = request()->ip();
        return json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
    }
}

function getIP()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    if ($ipaddress == '::1')
        $ipaddress = getHostByName(getHostName());

    return $ipaddress;
}

if (!function_exists('getAvatar')) {
    function getAvatar($path)
    {
        if (!empty($path)) {
            return $path;
        } else {
            // return asset('assets/img/card/personal.png');
            return asset('assets/img/default-profile.png');
        }
    }
}

if (!function_exists('getCover')) {
    function getCover($path = null)
    {
        if ($path) {
            $ppath = public_path($path);
            if (file_exists($ppath)) {
                return asset($path);
            } else {
                return asset('assets/img/default-cover.png');
            }
        } else {
            return asset('assets/img/default-cover.png');
        }
    }
}
if (!function_exists('getProfile')) {
    function getProfile($path = null)
    {
        if ($path) {
            $ppath = public_path($path);
            if (file_exists($ppath)) {
                return asset($path);
            } else {
                return asset('assets/images/default-user.png');
            }
        } else {
            return asset('assets/images/default-user.png');
        }

    }
}
if (!function_exists('getLogo')) {
    function getLogo($path = null)
    {
        if ($path) {
            $ppath = public_path($path);
            if (file_exists($ppath)) {
                return asset($path);
            } else {
                return asset('assets/images/default-logo.png');
            }
        } else {
            return asset('assets/images/default-logo.png');
        }
    }
}

if (!function_exists('getIcon')) {
    function getIcon($path = null)
    {
        if ($path) {
            $ppath = public_path($path);
            if (file_exists($ppath)) {
                return asset($path);
            } else {
                return asset('assets/images/default-icon.png');
            }
        } else {
            return asset('assets/images/default-icon.png');
        }
    }
}

if (!function_exists('getSeoImage')) {
    function getSeoImage($path = null)
    {
        if ($path) {
            $ppath = public_path($path);
            if (file_exists($ppath)) {
                return asset($path);
            } else {
                return asset('assets/images/default-seo.png');
            }
        } else {
            return asset('assets/images/default-seo.png');
        }
    }
}

if (!function_exists('getDesigComp')) {
    function getDesigComp($desig, $comp)
    {
        if ($desig != '' & $comp != '') {
            return $desig . ' At ' . $comp;
        } else {
            return $desig . ' ' . $comp;
        }

    }
}

if (!function_exists('makeUrl')) {
    function makeUrl($url)
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }
}

if (!function_exists('getSocialIcon')) {
    function getSocialIcon($ikey)
    {
        return DB::table('social_icon')->where('icon_name', '=', $ikey)->first();
    }
}

if (!function_exists('CurrencyFormat')) {
    function CurrencyFormat($number, $decimal = 1)
    { // cents: 0=never, 1=if needed, 2=always
        if (is_numeric($number)) { // a number
            if (!$number) { // zero
                $money = ($decimal == 2 ? '0.00' : '0.00'); // output zero
            } else { // value
                if (floor($number) == $number) { // whole number
                    $money = number_format($number, ($decimal == 2 ? 2 : 2)); // format
                } else { // cents
                    $money = number_format(round($number, 2), ($decimal == 0 ? 0 : 2)); // format
                } // integer or decimal
            } // value
            return $money;
        } // numeric
    } //
}

if (!function_exists('getConfigValue')) {

    function getConfigValue($key)
    {
        $config = DB::table('config')->where('config_key', $key)->first();
        return $config->config_value;
    }

}

if (!function_exists('userHasPlan')) {

    function userHasPlan()
    {
        $user = auth()->user();
        return $user && $user->plan_id !== null;
    }
}

if (!function_exists('getStatusText')) {

    function getStatusText($status)
    {
        switch ($status) {
            case 0:
                return "Pending";
            case 10:
                return "Published";
            case 20:
                return "Unpublished";
            case 30:
                return "Rejected";
            default:
                return "Expired";
        }
    }
}


if (!function_exists('userPlanPrivilege')) {

    function userPlanPrivilege()
    {
        $user = auth()->user();
        if ($user && isset($user->currentUserPlan->package_id) && $user->currentUserPlan->expired_date > now()) {
            return $user->currentUserPlan->package;
        } else {
            return false;
        }
    }
}



if (!function_exists('userBlogPrivilege')) {

    function userBlogPrivilege()
    {
        $user = auth()->user();
        if ($user && isset($user->currentUserPlan->package_id) && $user->currentUserPlan->expired_date > now()) {
            return $user->currentUserPlan->package;
        } else {
            return false;
        }
    }
}


if (!function_exists('getNotification')) {

    function getNotification($notify = 'user')
    {
        $user = auth()->user() ?? auth('api')->user();
        if ($user) {
            $query = Notification::where('notify_for', $notify)->latest();
            if ($notify == 'user') {
                $query->where('user_id', $user->id);
            }
            $notifications = $query->get();
            return $notifications->map(function ($notification) {
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
        } else {
            return null;
        }
    }
}


function formatFileName($file)
{
    $base_name = preg_replace('/\..+$/', '', $file->getClientOriginalName());
    $base_name = explode(' ', $base_name);
    $base_name = implode('-', $base_name);
    $base_name = Str::lower($base_name);
    $file_name = $base_name . "-" . uniqid() . "." . $file->getClientOriginalExtension();
    return $file_name;
}


function checkPackage($id = null)
{
    if ($id) {
        $user = DB::table('users')->where('id', $id)->first();
        if ($user->plan_id) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

function isFreePlan($user_id)
{
    $user = DB::table('users')->select('plans.is_free')->leftJoin('plans', 'plans.id', '=', 'users.plan_id')->where('users.id', $user_id)->first();
    if ($user->is_free == 1) {
        return true;
    }
    return false;
}

function isAnnualPlan($user_id)
{
    $user = DB::table('users')->select('users.*', 'plans.is_free')
        ->leftJoin('plans', 'plans.id', '=', 'users.plan_id')
        ->where('users.id', $user_id)
        ->first();
    $subscription_end = new \Carbon\Carbon($user->plan_validity);
    $subscription_start = new \Carbon\Carbon($user->plan_activation_date);
    $diff_in_days = $subscription_start->diffInDays($subscription_end);
    if ($diff_in_days > 364 && $user->is_free == 0) {
        return true;
    }
    return false;
}

function getPlan($user_id)
{
    return DB::table('users')
        ->select('plans.*')
        ->leftJoin('plans', 'plans.id', '=', 'users.plan_id')
        ->where('users.id', $user_id)
        ->first();
}

function uploadImage(?object $file, string $path, int $width, int $height, $watermark = false): string
{
    // $width = 850;
    // $height = 650;
    $blank_img = Image::canvas($width, $height, '#EBEEF7');
    $pathCreate = public_path("/uploads/$path/");
    if (!File::isDirectory($pathCreate)) {
        File::makeDirectory($pathCreate, 0777, true, true);
    }

    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    $updated_img = Image::make($file->getRealPath());
    $imageWidth = $updated_img->width();
    $imageHeight = $updated_img->height();
    if ($imageWidth > $width) {

        $updated_img->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
    if ($imageHeight > $height) {

        $updated_img->resize(null, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
    }


    $blank_img->insert($updated_img, 'center');
    $blank_img->save(public_path('/uploads/' . $path . '/') . $fileName);
    return "uploads/$path/" . $fileName;
}

function timeToDisplay($timer) {
    $hours = intval($timer / 3600, 10);
    $minutes = intval(($timer % 3600) / 60, 10);
    $seconds = intval($timer % 60, 10);

    $hours = $hours < 10 ? "0" . $hours : $hours;
    $minutes = $minutes < 10 ? "0" . $minutes : $minutes;
    $seconds = $seconds < 10 ? "0" . $seconds : $seconds;

    return $hours . ":" . $minutes . ":" . $seconds;
}

function getCommentReply($id)
{

    $auth_id = Auth::id();

    return BlogComment::with('getUser')
        ->leftJoin('blog_post_likes', function ($join) use ($auth_id) {
            $join->on('blog_comments.id', '=', 'blog_post_likes.comment_id')
                ->where('blog_post_likes.user_id', '=', $auth_id);
        })
        ->where('blog_comments.comment_parent_id', $id)
        ->where('blog_comments.status', '1')
        ->select('blog_comments.*', 'blog_post_likes.user_id as mylike_id', 'blog_post_likes.likedislike')
        ->get();

}

function getForumCommentReply($id)
{

    $auth_id = Auth::id();
    return ForumComment::with('getUser')
        ->leftJoin('forum_post_likes', function ($join) use ($auth_id) {
            $join->on('forum_comments.id', '=', 'forum_post_likes.comment_id')
                ->where('forum_post_likes.user_id', $auth_id);
        })
        ->where('comment_parent_id', $id)
        ->where('status', '1')
        ->select('forum_comments.*', 'forum_post_likes.user_id as mylike_id', 'forum_post_likes.likedislike')
        ->get();

}

function getForumLike($comment): array
{
    $data = [
        'status' => false,
        'like' => false,
        'dislike' => false
    ];
    if (Auth::check()) {
        $like = $comment->likeDislikes()->where('user_id', Auth::id())->first();
        if ($like) {
            $data['status'] = true;
            if ($like->likedislike === 1) {
                $data['like'] = true;
            } elseif ($like->likedislike === 0) {
                $data['dislike'] = true;
            }
        }
    }

    return $data;
}

if (!function_exists('getBookValidity')) {

    function getBookValidity()
    {
        $data['validDay'] = 30;
        $data['extraDay'] = 2;
        /*if (userPlanPrivilege() && userPlanPrivilege()->book == 1) {
            $data['extraDay'] = 30;
        }*/
        return $data;
    }

}

function createMARCRecord($title, $author, $isbn, $publisher, $date, $language = null)
{
    // Create a new MARC record
    $record = new File_MARC_Record();

    // Add a data field for the title
    $titleField = new File_MARC_Data_Field('245');
    $titleField->appendSubfield(new File_MARC_Subfield('a', $title));
    $record->appendField($titleField);

    // Add a data field for the author
    $authorField = new File_MARC_Data_Field('100');
    $authorField->appendSubfield(new File_MARC_Subfield('a', $author));
    $record->appendField($authorField);

    // Add a data field for the ISBN
    $isbnField = new File_MARC_Data_Field('020');
    $isbnField->appendSubfield(new File_MARC_Subfield('a', $isbn));
    $record->appendField($isbnField);

    // Add a data field for the publisher
    $publisherField = new File_MARC_Data_Field('260');
    $publisherField->appendSubfield(new File_MARC_Subfield('b', $publisher));
    $record->appendField($publisherField);

    // Add a data field for the publication date
    $dateField = new File_MARC_Data_Field('260');
    $dateField->appendSubfield(new File_MARC_Subfield('c', $date));
    $record->appendField($dateField);

    // Add a data field for the language
    $languageField = new File_MARC_Data_Field('041');
    $languageField->appendSubfield(new File_MARC_Subfield('a', $language));
    $record->appendField($languageField);


    return $record->toJSON();
}

/**
 * @param $setting
 * @param $email
 * @param $firstName
 * @param $lastName
 * @param string $role
 * @return void
 */
function subscribeToMailchimp($setting, $email, $firstName, $lastName, string $role): void
{

    try {
        $key = $setting->mailchimp_api_key ?? 'adf79b432fee63ba2dde5aaedde3ef20-us8';
        $MailChimp = new MailChimp($key);
        $list_id = $setting->mailchimp_list_id ?? '98d4cf1012';
        $result = $MailChimp->post("lists/$list_id/members", [
            'email_address' => $email,
            'status' => 'subscribed',
            'tags' => [$role],
            'merge_fields' => ['FNAME' => $firstName, 'LNAME' => $lastName ?? ' ']
        ]);
        $jsonResult = json_encode($result, JSON_PRETTY_PRINT);
        $path = 'fileManager/' . $email;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        file_put_contents($path . '/marc_record.json', $jsonResult);

    } catch (Exception $e) {
        Log::alert($e->getMessage());
    }

}

function is_reported($reportedId, $reporterId)
{
    return Report::where('reported_id', $reportedId)->where('reporter_id', $reporterId)->exists();
}

/**
 * @throws Exception
 */
function setConfigData(): void
{
    $paypalSettings = Setting::first();
    Config::set('paypal.mode', $paypalSettings->paypal_mode ?? 'sandbox');
    Config::set('paypal.sandbox.client_id', $paypalSettings->paypal_client_id ?? 'AXuKDkB6Z9UImbCI6WySYR-UbeLrr98JlmTTHQ4e0FSyIl2drxXX2YwHFWT3-tULLHwxlC-cyQd0LGRs');
    Config::set('paypal.sandbox.client_secret', $paypalSettings->paypal_client_secret ?? 'EFTCSMXDlZ0ALlhc8_1I-rAMKBlbiNEFw6yYFKq1cOxj8-R4rmuU1o6KGdjHMEXBlopHeo0lj1aJtTq9');
    Config::set('paypal.live.client_id', $paypalSettings->paypal_client_id ?? 'AXuKDkB6Z9UImbCI6WySYR-UbeLrr98JlmTTHQ4e0FSyIl2drxXX2YwHFWT3-tULLHwxlC-cyQd0LGRs');
    Config::set('paypal.live.client_secret', $paypalSettings->paypal_client_secret ?? 'EFTCSMXDlZ0ALlhc8_1I-rAMKBlbiNEFw6yYFKq1cOxj8-R4rmuU1o6KGdjHMEXBlopHeo0lj1aJtTq9');
}

function getEmbeddedUrl($url)
{
    $youtubePattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';

    if (preg_match($youtubePattern, $url, $matches)) {
        $videoId = $matches[1];
        return "https://www.youtube.com/embed/{$videoId}";
    } else {
        return "";
        $content = file_get_contents($url);
        if ($content !== false) {
            $contentType = mime_content_type($url);
            $uniqueFileName = uniqid() . "_" . time();
            $fileExtension = '';
            switch ($contentType) {
                case 'video/mp4':
                    $fileExtension = 'mp4';
                    break;
                case 'video/webm':
                    $fileExtension = 'webm';
                    break;
                case 'video/ogg':
                    $fileExtension = 'ogv';
                    break;
                case 'audio/mpeg':
                    $fileExtension = 'mp3';
                    break;
                case 'audio/wav':
                    $fileExtension = 'wav';
                    break;
                case 'audio/ogg':
                    $fileExtension = 'ogg';
                    break;
                default:
                    $fileExtension = 'dat';
                    break;
            }

            $localFilePath = "/uploads/product/file/{$uniqueFileName}.{$fileExtension}";
            file_put_contents($localFilePath, $content);

            return asset($localFilePath);
        } else {
            return "";
        }
    }
}

function getVideoID($url)
{
    $youtubePattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';

    if (preg_match($youtubePattern, $url, $matches)) {
        return $matches[1];
    } else {
       return '';
    }
}

function textLimit($title, $limit = 35): string
{
   return Str::limit($title, $limit, '...');
}

