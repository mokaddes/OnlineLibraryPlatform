<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogEventController;
use App\Http\Controllers\User\BlogController;
use App\Http\Controllers\User\BookController;
use App\Http\Controllers\User\ClubController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ForumEventController;
use App\Http\Controllers\User\TicketController;
use App\Http\Controllers\User\checkoutController;
use App\Http\Controllers\User\PromoCodeController;
use App\Http\Controllers\User\ReaderBookController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\User\FlutterWaveController;
use App\Http\Controllers\Auth\FacebookLoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\User\ProfileCompleteController;


// admin
// user


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['verify' => true]);
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('cc', function () {
    Artisan::call('optimize:clear');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    return "config cache cleared";
})->name('cc');

// Auth::routes(['verify' => true]);
Route::get('about', [HomeController::class, 'about'])->name('frontend.about');
Route::get('contact', [HomeController::class, 'contact'])->name('frontend.contact');
Route::post('contact-submit', [HomeController::class, 'contactSub'])->name('frontend.contact.submit');


Route::get('user/registration', [HomeController::class, 'registrationUser'])->name('user.registration');


Route::get('blogs/details/{slug}', [HomeController::class, 'blogsDetails'])->name('frontend.blogs.details');
Route::get('blogs/{category?}', [HomeController::class, 'blog'])->name('frontend.blogs');


Route::get('disclaimer', [HomeController::class, 'disclaimer'])->name('frontend.disclaimer');

Route::get('faq', [HomeController::class, 'faq'])->name('frontend.faq');
Route::get('pricing', [HomeController::class, 'pricing'])->name('frontend.pricing');
Route::get('forum/{category?}', [HomeController::class, 'forum'])->name('frontend.forum');
Route::get('forum-details/{slug}', [HomeController::class, 'forumDetails'])->name('frontend.forum.details');
Route::post('forum/report-user', [HomeController::class, 'reportUser'])->name('frontend.forum.report');
Route::get('privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('terms-condition', [HomeController::class, 'termsCondition'])->name('terms-condition');


Route::get('user/login', [LoginController::class, 'showLoginForm'])->name('user.login');
Route::post('user/login', [LoginController::class, 'login'])->name('user.login.submit');
Route::post('user/modal/login', [LoginController::class, 'modalLogin'])->name('user.modalLogin.submit');
Route::get('user/logout', [LoginController::class, 'logout'])->name('user.logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.forgotRequest');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.forgotEmail');
Route::post('/forgot-password-update', [ResetPasswordController::class, 'reset'])->name('password.forgotReset');

//Social Login
Route::get('google/login', [GoogleLoginController::class, 'redirectToProvider'])->name('google.login');
Route::get('google/login/callback', [GoogleLoginController::class, 'handleProviderCallback']);


Route::get('auth/facebook/login', [FacebookLoginController::class, 'redirectToFacebook']);
Route::get('auth/facebook/login/callback', [FacebookLoginController::class, 'handleFacebookCallback']);

Auth::routes();


Route::group(['as' => 'user.', 'prefix' => 'user', 'middleware' => ['auth:user','verified', 'profile']], function () {

    Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [UserDashboardController::class, 'settings'])->name('settings.index');
    Route::get('/transactions', [UserDashboardController::class, 'transactions'])->name('transaction.index');
    Route::post('{id}/profile/update', [UserDashboardController::class, 'update'])->name('profile.update');
    Route::post('{id}/password/update', [UserDashboardController::class, 'passChange'])->name('password.update');
    Route::post('{id}/account/delete', [UserDashboardController::class, 'Delete'])->name('account.delete');
    Route::post('{id}/billing-info/update', [UserDashboardController::class, 'billing'])->name('billing.update');


    Route::get('{id}/checkout/{flag?}', [checkoutController::class, 'index'])->name('checkout');
    Route::post('{id}/checkout/submit', [checkoutController::class, 'submit'])->name('checkout.submit');

    Route::group(['prefix' => 'blogevent', 'as' => 'blogevent.'], function () {
        Route::post('/commentstore', [BlogEventController::class, 'commentstore'])->name('commentstore');
        Route::post('{id}/commentupdate', [BlogEventController::class, 'commentupdate'])->name('commentupdate');
        Route::post('/commentLike', [BlogEventController::class, 'commentLike'])->name('commentLike');
        Route::delete('{id}/commentdelete', [BlogEventController::class, 'commentDelete'])->name('commentdelete');
    });

    Route::group(['prefix' => 'order-list', 'as' => 'order.'], function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
    });
    Route::post('/payment-request', [OrderController::class, 'paymentRequest'])->name('payment.request.submit');

    Route::group(['prefix' => 'forumevent', 'as' => 'forumevent.'], function () {
        Route::post('/commentstore', [ForumEventController::class, 'commentstore'])->name('commentstore');
        Route::post('/commentLike', [ForumEventController::class, 'commentLike'])->name('commentLike');
    });

    Route::post('forum/ask/question', [HomeController::class, 'forumAskQuestion'])->name('forum.ask.question');
    Route::group(['prefix' => 'support-ticket', 'as' => 'ticket.'], function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('create', [TicketController::class, 'create'])->name('create');
        Route::post('store', [TicketController::class, 'store'])->name('store');
        Route::get('{id}/view', [TicketController::class, 'view'])->name('view');
        Route::post('reply', [TicketController::class, 'reply'])->name('reply');
        Route::get('{id}/delete', [TicketController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'club', 'as' => 'club.'], function () {
        Route::get('/', [ClubController::class, 'index'])->name('index');
        Route::get('/{id}/details', [ClubController::class, 'clubDetails'])->name('joinclub');
        Route::get('/{id}/members', [ClubController::class, 'clubMembers'])->name('clubMembers');
        Route::get('/{id}/settings', [ClubController::class, 'clubSettings'])->name('clubSettings');
        Route::get('/{id}/posts', [ClubController::class, 'clubPosts'])->name('clubPosts');
        Route::post('/join', [ClubController::class, 'joinclub'])->name('joinclub.submit');
        Route::post('/leave', [ClubController::class, 'leave'])->name('leave');
        Route::get('/question/{id}', [ClubController::class, 'question'])->name('question');
        Route::post('/reply', [ClubController::class, 'reply'])->name('question.reply');
        Route::delete('/{id}/reply-delete', [ClubController::class, 'replyDelete'])->name('reply.delete');
        Route::post('/{id}/reply-update', [ClubController::class, 'replyUpdate'])->name('reply.update');
        Route::get('/member/{id}/status/{status?}', [ClubController::class, 'changeStatus'])->name('status.change');
        Route::get('/{id}/ask-question', [ClubController::class, 'askQuestion'])->name('question.ask');
        Route::post('/question-submit', [ClubController::class, 'submitQuestion'])->name('question.submit');
        Route::get('/create', [ClubController::class, 'create'])->name('create');
        Route::post('/store', [ClubController::class, 'store'])->name('store');
        Route::post('{id}/update', [ClubController::class, 'update'])->name('update');
    });



    //Blog
    Route::group(['prefix' => 'blog', 'as' => 'blog.'], function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/store', [BlogController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [BlogController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [BlogController::class, 'update'])->name('update');
        Route::get('/{id}/delete', [BlogController::class, 'delete'])->name('delete');
    });



    Route::group(['prefix' => 'books', 'as' => 'book.'], function () {
        Route::get('/all-books', [ReaderBookController::class, 'index'])->name('index');
        Route::get('/borrowed', [ReaderBookController::class, 'borrowed'])->name('borrowed');
        Route::get('/favourite', [ReaderBookController::class, 'favourite'])->name('favourite');
        Route::get('/details/{slug}', [ReaderBookController::class, 'details'])->name('details');
        Route::get('/read/{slug}', [ReaderBookController::class, 'read'])->name('read');
        Route::get('{id}/borrowed/store', [ReaderBookController::class, 'borrowedStore'])->name('borrowed.store');
        Route::get('{id}/favorite/store', [ReaderBookController::class, 'favoriteStore'])->name('favorite.store');
        Route::post('/{id}/review', [ReaderBookController::class, 'review'])->name('review');
        Route::get('/{id}/review/delete', [ReaderBookController::class, 'reviewDelete'])->name('review.delete');
        Route::get('/{id}/progress', [ReaderBookController::class, 'progress'])->name('progress');
    });


    Route::get('{id}/subscription', [FlutterWaveController::class, 'subscribe'])->name('package.subscribe');
    Route::get('/subscribe/success', [FlutterWaveController::class, 'subscribeSuccess'])->name('subscribe.success');
    Route::get('/subscribe/cancel', [FlutterWaveController::class, 'subscribeCancel'])->name('subscribe.cancel');

    Route::get('promo-code/apply', [PromoCodeController::class, 'index'])->name('promo-code.index');
    Route::post('promo-code/store', [PromoCodeController::class, 'store'])->name('promo-code.store');



});

Route::group(['as' => 'user.', 'prefix' => 'user', 'middleware' => ['auth:user','verified']], function () {
    Route::get('/complete-profile', [ProfileCompleteController::class, 'index'])->name('profile.complete');
    Route::post('/complete-profile/update', [ProfileCompleteController::class, 'profileComplete'])->name('profile.complete.update');
});

Route::group(['as' => 'author.', 'prefix' => 'author', 'middleware' => ['auth']], function () {
    Route::group(['prefix' => 'my-books', 'as' => 'books.'], function () {
        Route::get('/', [BookController::class, 'myBooks'])->name('index');
        Route::get('/pending', [BookController::class, 'pendingBooks'])->name('pending');
        Route::get('/declined', [BookController::class, 'declinedBooks'])->name('declined');
        Route::get('/readers', [BookController::class, 'book_readers'])->name('readers');
        Route::get('create', [BookController::class, 'create'])->name('create');
        Route::post('store', [BookController::class, 'store'])->name('store');
        Route::get('{id}/edit', [BookController::class, 'edit'])->name('edit');
        Route::post('update', [BookController::class, 'update'])->name('update');
        Route::get('{id}/view', [BookController::class, 'view'])->name('view');
        Route::get('{id}/analytic', [BookController::class, 'analytic'])->name('analytic');
        Route::get('{id}/delete', [BookController::class, 'delete'])->name('delete');
    });
});

Route::get('test/mail', [HomeController::class, 'testMail']);
Route::post('send/mail', [HomeController::class, 'sendTestMail'])->name('sendTestMail');
Route::get('/read-notification/{userId}/{notifyId}', [HomeController::class, 'readNotification'])->name('readNotification');
Route::get('/book/reminder', [HomeController::class, 'bookRemainder'])->name('bookRemainder');


