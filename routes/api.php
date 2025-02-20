<?php

use App\Http\Controllers\Api\ClubController;
use App\Http\Controllers\Api\ForumController;
use App\Http\Controllers\Api\InAppPurchaseController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PayPalController;
use App\Http\Controllers\Paypal\PaypalWebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/social-login', [AuthController::class, 'socialLogin']);
Route::post('/forgot/password', [AuthController::class, 'forgotPassword']);
Route::post('paypal/webhook', [PaypalWebhookController::class, 'handleWebhook'])->name('paypal.webhook');

Route::middleware('auth:api')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/dashboard', [AuthController::class, 'dashboard']);
    Route::get('/transactions', [AuthController::class, 'transactions']);
    Route::post('/profile/update', [AuthController::class, 'profileUpdate']);
    Route::post('/profile/complete', [AuthController::class, 'profileComplete']);
    Route::post('/password/update', [AuthController::class, 'passwordUpdate']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user/delete', [AuthController::class, 'delete']);
    Route::post('/apply/promo-code', [AuthController::class, 'promoApply']);
    Route::post('/billing/update', [AuthController::class, 'billing']);

    Route::group(['prefix' => 'support-ticket', 'as' => 'ticket.'], function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::post('store', [TicketController::class, 'store'])->name('store');
        Route::get('{id}/view', [TicketController::class, 'view'])->name('view');
        Route::post('reply', [TicketController::class, 'reply'])->name('reply');
        Route::get('{id}/delete', [TicketController::class, 'delete'])->name('delete');
    });


    Route::group(['prefix' => 'books', 'as' => 'api.book.'], function () {
        Route::get('/all', [BookController::class, 'index'])->name('index');
        Route::get('/borrowed', [BookController::class, 'borrowed'])->name('borrowed');
        Route::get('/favorite', [BookController::class, 'favorite'])->name('favorite');
        Route::post('{id}/borrowed/store', [BookController::class, 'borrowedStore'])->name('borrowed.store');
        Route::post('{id}/favorite/store', [BookController::class, 'favoriteStore'])->name('favorite.store');
        Route::get('/viewed', [BookController::class, 'viewed'])->name('viewed');
        Route::get('/my_books', [BookController::class, 'my_books'])->name('my_books');
        Route::get('/pending', [BookController::class, 'pending_books'])->name('pending');
        Route::get('/declined', [BookController::class, 'declined_books'])->name('declined');
        Route::get('/my-readers', [BookController::class, 'my_readers'])->name('my-readers');
        Route::post('store', [BookController::class, 'store'])->name('store');
        Route::post('{id}/update', [BookController::class, 'update'])->name('update');
        Route::get('{id}/details', [BookController::class, 'details'])->name('view');
        Route::get('{id}/delete', [BookController::class, 'delete'])->name('delete');
        Route::get('{id}/analytic', [BookController::class, 'analytic'])->name('analytic');
        Route::post('review', [BookController::class, 'review'])->name('review');
        Route::post('/{id}/review/delete', [BookController::class, 'reviewDelete'])->name('review.delete');
        Route::get('getSearchData', [BookController::class, 'getSearchData'])->name('getSearchData');
        Route::post('/{id}/read-page', [BookController::class, 'readPage'])->name('read-page');
        Route::post('/{id}/progress', [BookController::class, 'progress'])->name('progress');
    });
    Route::group(['prefix' => 'forum', 'as' => 'forum.'], function () {
        Route::get('my-questions/', [ForumController::class, 'myQuestions'])->name('my-questions');
        Route::post('question/store', [ForumController::class, 'store'])->name('store');
        Route::post('question/{id}/update', [ForumController::class, 'update'])->name('update');
        Route::get('question/{id}/delete', [ForumController::class, 'delete'])->name('delete');
        Route::post('question/{id}/comment', [ForumController::class, 'comment'])->name('comment');
        Route::post('question/likeDislike', [ForumController::class, 'likeDislike'])->name('likeDislike');

    });

    Route::group(['prefix' => 'club'], function () {
        Route::get('/', [ClubController::class, 'index'])->name('index');
        Route::get('/{id}/details', [ClubController::class, 'clubDetails']);
        Route::post('/store', [ClubController::class, 'store'])->name('store');
        Route::post('{id}/update', [ClubController::class, 'update'])->name('update');

        Route::post('/join', [ClubController::class, 'joinClub']);
        Route::post('/leave', [ClubController::class, 'leave']);
        Route::get('/question/{id}', [ClubController::class, 'question']);
        Route::get('/member/{id}/status/{status?}', [ClubController::class, 'changeStatus']);

        Route::post('post/store', [ClubController::class, 'submitQuestion']);
        Route::get('/post/{id}/details', [ClubController::class, 'question']);
        Route::post('/post/reply', [ClubController::class, 'reply']);

    });

    Route::get('get/notification', [NotificationController::class, 'getNotification']);
    Route::get('read/notification', [NotificationController::class, 'readNotification']);

    Route::post('{id}/payment/submit', [PayPalController::class, 'submit']);
    Route::post('{id}/flutter-wave/submit', [PayPalController::class, 'flutterSubmit']);
    Route::post('in-app/submit', [InAppPurchaseController::class, 'submit']);


    Route::get('orders', [OrderController::class, 'index']);
    Route::post('payment-request', [OrderController::class, 'paymentRequest']);

    Route::post('book/{id}/buy', [PayPalController::class, 'bookBuy']);
});

Route::get('/paypal/success', [PayPalController::class, 'success']);

Route::get('/category', [HomeController::class, 'category']);
Route::get('/faq', [HomeController::class, 'faq']);

Route::get('/blogs/{category?}', [BlogController::class, 'index']);
Route::get('/blog/details/{id}', [BlogController::class, 'details']);
Route::get('/blog/categories', [BlogController::class, 'category']);
Route::get('/blog/tags', [BlogController::class, 'tags']);

Route::get('/forums/{category?}', [ForumController::class, 'index']);
Route::get('/forum/details/{id}', [ForumController::class, 'details']);
Route::get('/forum/categories', [ForumController::class, 'category']);
Route::get('/forum/tags', [ForumController::class, 'tags']);

Route::get('/home-page', [HomeController::class, 'homePage']);
Route::get('/custom-page', [HomeController::class, 'customPage']);
Route::get('/pricing', [HomeController::class, 'package']);

Route::get('/settings', [HomeController::class, 'settings']);


