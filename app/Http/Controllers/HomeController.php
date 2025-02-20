<?php

namespace App\Http\Controllers;

use App\Mail\Allmail;
use App\Mail\SendContact;
use App\Models\Admin;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogTag;
use App\Models\BorrowedBook;
use App\Models\Category;
use App\Models\Contact;
use App\Models\CustomPage;
use App\Models\Faq;
use App\Models\Forum;
use App\Models\ForumCategory;
use App\Models\ForumComment;
use App\Models\ForumTag;
use App\Models\Package;
use App\Models\Product;
use App\Models\ProductView;
use App\Models\Report;
use App\Models\Setting;
use App\Models\User;
use App\Traits\Notification;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    use Notification;

    public function index()
    {
        $data['title'] = 'Home';
        $data['og_title'] = 'N/A';
        $data['og_description'] = '';
        $data['og_image'] = '';
        $data['categories'] = Category::where('status', '1')->orderBy('order_number', 'asc')->take(10)->get();
        $data['highlights'] = Product::where('status', '10')->where('is_highlight', '1')->latest()->take(8)->get();
        $data['book_of_month'] = Product::where('status', '10')->where('is_book_of_month', '1')->latest()->first();
        $data['section'] = DB::table('home_page')->where('id', 1)->first();
        $data['setting'] = Setting::first();
        return view('frontend.index', $data);
    }

    public function privacyPolicy()
    {
        $data['title'] = 'Privacy Policy';
        $data['og_title'] = '';
        $data['og_description'] = '';
        $data['og_image'] = '';
        $data['row'] = CustomPage::where('url_slug', 'privacy-policy')->first();
        return view('frontend.custom_page', $data);
    }

    public function termsCondition()
    {
        $data['title'] = 'Terms Condition';
        $data['og_title'] = '';
        $data['og_description'] = '';
        $data['og_image'] = '';
        $data['row'] = CustomPage::where('url_slug', 'terms-and-conditions')->first();
        return view('frontend.custom_page', $data);
    }

    public function faq()
    {
        $data['title'] = 'FAQ';
        $data['og_title'] = '';
        $data['og_description'] = '';
        $data['og_image'] = '';
        $data['faqs'] = Faq::where('is_active', '1')->orderBy('order_id', 'asc')->take(5)->get();
        return view('frontend.faq', $data);
    }


    public function pricing(Request $request)
    {
        $data['title'] = 'Pricing';
        $data['og_title'] = '';
        $data['og_description'] = '';
        $data['og_image'] = '';
        $data['packages'] = Package::where('status', 1)->get();
        return view('frontend.pricing', $data);
    }

    public function registrationUser()
    {
        getUserLocation();
        $data['title'] = 'Registration';
        $data['og_title'] = '';
        $data['og_description'] = '';
        $data['og_image'] = '';
        return view('auth.register', $data);
    }

    public function blog(Request $request, $category = null)
    {

        $rows = Blog::orderBy('is_top', 'desc')->where('status', 1)->latest();
        if (request()->has('search')) {
            $searchQuery = $request->search;
            $rows->where('title', 'like', "%$searchQuery%");
        }
        if ($category) {
            $cat = BlogCategory::where('slug', $category)->first();
            $blog_ids = DB::table('blog_category_map')->groupBy('blog_id')->where('blog_category_id', $cat->id)->pluck('blog_id')->toArray();
            $rows->whereIn('id', $blog_ids);
        }
        if (request()->has('tag')) {
            $blog_ids = DB::table('blog_tags')->groupBy('blog_id')->where('slug', $request->tag)->pluck('blog_id')->toArray();
            $rows->whereIn('id', $blog_ids);
        }
        $tag = $request->tag ?? '';
        $category = $category ?? '';
        $rows = $rows->paginate(10);
        $blogCategories = BlogCategory::withCount('blogs')->get();
        $blogTags = BlogTag::groupBy('name')->get();
        return view('frontend.blog', compact('rows', 'blogCategories', 'category', 'blogTags', 'tag'));
    }

    public function about()
    {
        $data['title'] = 'About';
        $data['og_title'] = '';
        $data['og_description'] = '';
        $data['og_image'] = '';
        $data['aboput'] = '';

        return view('frontend.about', $data);
    }

    public function contact()
    {
        $data['title'] = 'Contact';
        $data['og_title'] = '';
        $data['og_description'] = '';
        $data['og_image'] = '';
        $data['contact'] = '';
        $data['settings'] =  Setting::first();


        return view('frontend.contact', $data);
    }

    public function contactSub(Request $request)
    {
        $setting =  Setting::first();
        $g_captcha = 'nullable';
        if ($setting && $setting->recaptcha_enable == 1) {
            $g_captcha = 'required';
        }
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'g-recaptcha-response' => $g_captcha,
            'message' => 'required'
        ],[
            'g-recaptcha-response.required' => 'Google captcha response is failed. please try again.'
        ]);
        DB::beginTransaction();
        try {
            $contact = new Contact();
            $contact->name = $request->name;
            $contact->email = $request->email;
            $contact->reason = $request->subject;
            $contact->message = $request->message;
            $contact->save();

            $data = [];
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['message'] = $request->message;
            //if mail exist
            $support_email = $setting->email ?? $setting->support_email;
            if ($support_email) {
                Mail::to($support_email)->send(new SendContact($data));
            }
            $title = 'A contact form submitted.';
            $action = 'admin.contact.index';
            $admin_id = Admin::first()->id;
            $this->saveAdminNotification($title, $action, 'contact', $admin_id);
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Unable to send your query'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back();
        }
        DB::commit();
        Toastr::success(trans('Your query is submitted'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

    public function disclaimer()
    {
        $data['title'] = 'disclaimer';
        $data['og_title'] = '';
        $data['og_description'] = '';
        $data['og_image'] = '';
        $data['disclaimer'] = '';
        return view('frontend.disclaimer', $data);
    }

    public function forum(Request $request, $category = null, $tag = null)
    {
        $data['title'] = 'Forum';
        $data['cats'] = ForumCategory::get();
        // $rows = Forum::withCount('getComment')->where('status', 1)->orderBy('id', 'desc')->latest()->get();
        $rows = Forum::withCount(['getComment' => function ($query) {
            $query->where('status', 1);
        }])->where('status', 1)->orderBy('id', 'desc')->latest();
        if ($category) {
            $cat = ForumCategory::where('slug', $category)->first();
            // dd($cat);
            $forum_ids = DB::table('forums')->groupBy('id')->where('category_id', $cat->id)->pluck('id')->toArray();
            // dd($forum_ids);
            $rows->whereIn('id', $forum_ids);
            //  dd($rows);
        }
        if (request()->has('tag')) {
            $forum_ids = DB::table('forum_tags')->groupBy('forum_id')->where('slug', $request->tag)->pluck('forum_id')->toArray();
            $rows->whereIn('id', $forum_ids);
        }
        $rows = $rows->where('status', 1)->paginate(5);
        $recentQuestions = Forum::orderBy('id', 'desc')->where('status', '1')->take(5)->get();
        $categoryWiseForum = ForumCategory::withCount(['forums' => function ($query) {
            $query->where('status', 1);
        }])->where('status', '1')->orderBy('order_number', 'asc')->get();
        $forumTags = ForumTag::with('forum')
            ->whereHas('forum', function ($query) {
                $query->where('status', 1);
            })
            ->groupBy('name')
            ->get();
        return view('frontend.forum', compact('data', 'rows', 'recentQuestions', 'categoryWiseForum', 'forumTags'));
    }

    public function forumAskQuestion(Request $request)
    {
        $request->validate([
            'title' => 'required|max:100',
            'category_id' => 'required',
            'tags' => 'required',
            'descriptions' => 'required',
        ]);
        DB::beginTransaction();
        try {

            $slug = Str::slug($request->title);
            $check_slug = Forum::where('slug', $slug)->first();

            if ($check_slug) {
                $uniqueId = Str::uuid()->toString();
                $slug = $slug . '-' . $uniqueId;
            }

            $forum = new Forum();
            $forum->title = $request->title;
            $forum->slug = $slug;
            $forum->status = 0;
            $forum->created_by = Auth::id();
            $forum->category_id = $request->category_id;
            $forum->descriptions = $request->descriptions;
            $result = $forum->save();
            if ($request->tags) {
                $tagsarr = explode(',', $request->tags);
                foreach ($tagsarr as $key => $value) {
                    $forum_tags = new ForumTag();
                    $forum_tags->forum_id = $forum->id;
                    $forum_tags->slug = Str::slug($value);
                    $forum_tags->name = $value;
                    $forum_tags->save();
                }
            }
            if ($result) {
                $name = $forum->getUser->name . ' ' . $forum->getUser->last_name;
                $data = [
                    'admin_email' => getSetting()->support_email,
                    'template' => 'forumQuestion',
                    'subject' => 'A New Forum Question Has Been Submitted!',
                    'greeting' => 'Hello, Admin,',
                    'body' => 'A new forum question has been received from user - ' . $name . '. Please review and respond to the users forum question as soon as possible.',
                    'link' => route('admin.forum.index'),
                    'msg' => 'Click here to navigate to the Forum page',
                    'thanks' => 'Thank you and stay with ' . ' ' . config('app.name'),
                    'site_url' => route('home'),
                    'footer' => '0',
                    'site_name' => config('app.name'),
                    'copyright' => ' © ' . ' ' . Carbon::now()->format('Y') . config('app.name') . ' ' . 'All rights reserved.',
                ];
                // if ($settings->app_mode == 'live') {
                Mail::to($data['admin_email'])->send(new Allmail($data));
                // }
            }
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error(trans('Forum Question not Created !'), 'Error', ["positionClass" => "toast-top-right"]);
            return redirect()->back()->withInput();
        }
        DB::commit();
        Toastr::success(trans('Question Submitted Successfully!, Please wait for admin approval'), 'Success', ["positionClass" => "toast-top-right"]);
        return redirect()->back();
    }

    public function forumDetails($slug)
    {
        // dd($slug);
        $data['title'] = 'Forum';

        $auth_id = Auth::id();
        $total_comments = 0;
        $forumDetails = Forum::with('getUser')->where('slug', $slug)->first();
        $total_comments = ForumComment::where('forum_id', $forumDetails->id)->where('status', 1)->count();
        /*$comments = ForumComment::with('getUser')
            ->leftJoin('forum_post_likes', function ($join) use ($auth_id) {
                $join->on('forum_comments.id', '=', 'forum_post_likes.comment_id')
                    ->where('forum_post_likes.user_id', $auth_id);
            })
            ->where('forum_comments.forum_id', $forumDetails->id)
            ->where('forum_comments.comment_parent_id', '0')
            ->where('forum_comments.status', '1')
            ->select('forum_comments.*', 'forum_post_likes.user_id as mylike_id', 'forum_post_likes.likedislike')
            ->get();*/
        $comments = ForumComment::with('getUser', 'replies', 'likeDislikes')->where('status', 1)->where('forum_id', $forumDetails->id)->where('comment_parent_id', 0)->get();

        $recentQuestions = Forum::orderBy('id', 'desc')->where('status', '1')->take(5)->get();
        $categoryWiseForum = ForumCategory::withCount(['forums' => function ($query) {
            $query->where('status', 1);
        }])->where('status', '1')->orderBy('order_number', 'asc')->get();
        $forumTags = ForumTag::with('forum')
            ->whereHas('forum', function ($query) {
                $query->where('status', 1);
            })
            ->groupBy('name')
            ->get();
        return view('frontend.forums.forum_details', compact('forumDetails', 'comments', 'total_comments', 'recentQuestions', 'categoryWiseForum', 'forumTags'));
    }

    public function reportUser(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        $report = new Report();
        $report->reporter_id = Auth::user()->id;
        $report->reported_id = $request->reported_id;
        $report->forum_id = $request->forum_id;
        $report->message = $request->message;
        $report->status = 1;
        $report->report_for = 'forum';
        $report->save();

        Toastr::success(trans('You are successfully reported.'), 'Success', ["positionClass" => "toast-top-right"]);
        return back();


    }

    public function blogs()
    {
        $data['title'] = 'Blogs';
        $data['og_title'] = '';
        $data['og_description'] = '';
        $data['og_image'] = '';
        $data['blogs'] = '';
        return view('frontend.blogs.index', $data);
    }

    public function blogsDetails($slug)
    {
        $auth_id = Auth::id();
        $total_comments = 0;
        $blogDetails = Blog::with('getCategoryMap')->where('slug', $slug)->first();
        // 1,2
        $relatedBlogsIds = DB::table('blog_category_map')
            ->join('blogs', 'blog_category_map.blog_id', '=', 'blogs.id')
            ->where('blog_id', $blogDetails->id)
            ->pluck('blog_category_id')->toArray();
        $blogs = Blog::with('getCategoryMap')
            ->join('blog_category_map', 'blogs.id', '=', 'blog_category_map.blog_id')
            ->leftJoin('blog_categories', function ($join) use ($auth_id) {
                $join->on('blog_category_map.blog_category_id', '=', 'blog_categories.id');
            })
            ->whereIn('blog_categories.id', $relatedBlogsIds)
            ->where('blogs.slug', '!=', $slug)
            ->select('blogs.*')
            ->get();

        $blogCategories = BlogCategory::withCount('blogs')->get();
        $total_comments = BlogComment::where('blog_post_id', $blogDetails->id)->where('blog_comments.status', 1)->count();
        $comments = BlogComment::with('getUser')
            ->leftJoin('blog_post_likes', function ($join) use ($auth_id) {
                $join->on('blog_comments.id', '=', 'blog_post_likes.comment_id');
                $join->on('blog_post_likes.user_id', '=', DB::raw("'" . $auth_id . "'"));
            })
            ->where('blog_comments.blog_post_id', $blogDetails->id)
            ->where('blog_comments.comment_parent_id', '0')
            ->where('blog_comments.status', '1')
            ->select('blog_comments.*', 'blog_post_likes.user_id as mylike_id', 'blog_post_likes.likedislike')
            ->get();


        return view('frontend.blogs.details', compact('blogDetails', 'blogs', 'blogCategories', 'comments', 'total_comments'));
    }

    public function blogCategories($slug)
    {
        $blog = Blog::get();
        $blogCategories = BlogCategory::withCount('blogs')->get();
        $blogTags = BlogTag::get();
        $categoryWiseBlogs = BlogCategory::where('slug', $slug)
            ->join('blog_category_map', 'blog_categories.id', '=', 'blog_category_map.blog_category_id')
            ->select('blog_category_map.*')
            ->get();
        $blogIds = $categoryWiseBlogs->pluck('blog_id');
        $data['rows'] = Blog::whereIn('id', $blogIds)->get();
        return view('frontend.blogs.categoryWiseBlogs', compact('data', 'blog', 'blogCategories', 'blogTags', 'slug'));
    }

    public function userRegister(Request $request)
    {
        $setting = getSetting();

        $request->validate([
            'name' => "required",
            'email' => "required|email|unique:users,email",
            'password' => "required|confirmed|min:8|max:50",
        ]);

        $created = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($created) {
            Auth::guard('user')->logout();
            Auth::guard('admin')->logout();
            Auth::guard('user')->login($created);
            if ($setting->customer_email_verification) {
                return redirect()->route('verification.notice');
            } else {
                return redirect()->route('user.dashboard');
            }
        }


    }

    public function login()
    {
        $data['title'] = 'Login';
        $data['og_title'] = '';
        $data['og_description'] = '';
        $data['og_image'] = '';

        return view('frontend.login', $data);
    }

    public function testMail()
    {
        return view('frontend.test_mail');

    }

    public function flip()
    {
        return view('user.book.reader.read');

    }

    public function sendTestMail(Request $request)
    {
        try {
            $email = $request->email ?? 'ronymia.tech@gmail.com';
            $data = [
                'site_name' => 'TCL',
                'subject' => 'This is test mail',
                'greeting' => 'Hi Mr/Mrs,',
                'body' => 'This is a test mail from our site.',
                'actionText' => 'Visit Website',
                'site_url' => route('home'),
                'template' => 'test',
            ];
            // Notification::route('mail', $email)->notify(new UserRegisterMailNotification($details));
            $ss = Mail::to($email)->send(new Allmail($data));


            Toastr::success(trans('Mail Sent Successfully!'), 'Success', ["positionClass" => "toast-top-right"]);
        } catch (\Exception $e) {
            dd('error: ' . $e->getMessage());
        }
        return redirect()->back();
    }

    public function readNotification($userId, $notifyId)
    {
        if ($notifyId == 'all') {
            \App\Models\Notification::where('user_id', $userId)->update(['is_read' => 1]);
            $reload = "true";
        } elseif ($notifyId == 'admin') {
            $reload = "true";
            \App\Models\Notification::where('notify_for', 'admin')->update(['is_read' => 1]);
        } else {
            \App\Models\Notification::where('id', $notifyId)->update(['is_read' => 1]);
        }
        $newCount = \App\Models\Notification::where('user_id', $userId)->where('is_read', 0)->count();
        return response()->json(['success' => true, 'count' => $newCount, 'load' => $reload ?? '']);
    }

    public function bookRemainder()
    {

        $inProgress = ProductView::whereHas('book', function ($book) {
            $book->whereHas('borrowedBooks', function ($q) {
                $q->where('is_valid', 1)->where('borrowed_enddate', '>', now());
            })->where('status', '10');
        })->whereHas('user', function ($user) {
            $user->where('status', '1')->where('role_id', '1');
        })->where(function ($query) {
            $query->where('progress', '<', 100)
                ->orWhereNull('progress');
        })->get();
        $borrowed = BorrowedBook::whereHas('book', function ($book) {
            $book->whereDoesntHave('productViews')->where('status', '10');
        })->whereHas('user', function ($user) {
            $user->where('status', '1')->where('role_id', '1');
        })->where('is_valid', 1)->where('borrowed_enddate', '>', now())->get();
        $inCompletes = $inProgress->merge($borrowed);
        foreach ($inCompletes as $item) {
            $title = 'You have incomplete reading of "' . $item->book->title . '"';
            $action = 'user.book.details,' . $item->book->slug;
            $this->saveUserNotification($title, $action, 'book_remainder', 'user', $item->user_id);
            $this->sendUserPushNotification([$item->user_id], 'Reminder' ,$title, 'book_remainder');
        }
        return true;

    }




}
