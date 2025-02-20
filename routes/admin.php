<?php


use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\PromoCodeBookController;
use App\Http\Controllers\Admin\PromoCodePackageController;
use App\Http\Controllers\Admin\PushNotificationController;
use App\Http\Controllers\Admin\SubscribeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NewsLetterController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\Admin\BlogCommentsController;

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



//====================Admin Authentication=========================

Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('login.admin');
Route::post('admin/login', [AdminLoginController::class, 'login'])->name('admin.login');
Route::get('admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');


Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth:admin'], 'where' => ['locale' => '[a-zA-Z]{2}']], function () {
    // {{--('BRAND')--}}
    Route::get('/view', [NewsLetterController::class, 'index'])->name('newsletter.list');

    Route::get('/brand', [BrandController::class, 'view'])->name('brand.index');
    Route::get('/add', [BrandController::class, 'create'])->name('brand.create');
    Route::post('/add', [BrandController::class, 'store'])->name('brand.store');
    Route::get('/edit/{brand}', [BrandController::class, 'edit'])->name('admin.brand.edit');
    Route::put('/update/{brand}', [BrandController::class, 'update'])->name('admin.brand.update');
    Route::get('/{brand:slug}/ads', [BrandController::class, 'show'])->name('admin.brand.show');
    Route::delete('/destroy/{brand}', [BrandController::class, 'destroy'])->name('admin.brand.destroy');

    Route::get('/sc', [SettingsController::class, 'setview'])->name('settings.MobileApp.index');
    Route::post('/sc/update', [SettingsController::class, 'MobileAppUpdate'])->name('settings.MobileApp.update');

    Route::get('/currency', [CurrencyController::class, 'currenview'])->name('settings.Currency.index');

    // Route::get('/general',[GeneralController::class,'genview'])->name('settings.General.general');
    Route::get('/smtp', [MailController::class, 'mailview'])->name('settings.Smtp.mail');
    Route::post('/smtp/update', [SettingsController::class, 'SmtpUpdate'])->name('settings.smtp.update');


    // Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@dashboard']);
    Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@admin_dashboard']);
    Route::get('setting-profile', 'SettingsController@setting')->name('setting');
    Route::post('{id}/profile/update', 'SettingsController@profileUpdate')->name('profile.update');
    Route::post('{id}/password/update', 'SettingsController@passUpdate')->name('password.update');

    Route::get('/cc', 'DashboardController@cacheClear')->name('cacheClear');
    // Route::get('settings', ['as' => 'settings', 'uses' => 'SettingsController@settings']);


    //Custom Page
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('general', 'SettingsController@general')->name('general');
        Route::post('general/store', 'SettingsController@generalStore')->name('general_store');
        Route::get('languages', 'LanguageController@index')->name('language');
        Route::post('language/store', 'LanguageController@store')->name('language.store');
        Route::get('language/{id}/edit', 'LanguageController@edit')->name('language.edit');
        Route::post('language/{id}/update', 'LanguageController@update')->name('language.update');
        Route::get('language/{id}/delete', 'LanguageController@delete')->name('language.delete');
    });




    Route::get('ajax/text-editor/image', ['as' => 'text-editor.image', 'uses' => 'CustomPageController@postEditorImageUpload']);
    //Custom Page
    Route::group(['prefix' => 'cpage', 'as' => 'cpage.'], function () {
        Route::get('/', 'CustomPageController@index')->name('index');
        Route::get('create', 'CustomPageController@create')->name('create');
        // Route::post('store', 'CustomPageController@store')->name('store');
        Route::get('{id}/view', 'CustomPageController@view')->name('view');
        Route::get('{id}/edit', 'CustomPageController@edit')->name('edit');
        Route::post('{id}/update', 'CustomPageController@update')->name('update');
        Route::get('{id}/delete', 'CustomPageController@getDelete')->name('delete');
        Route::get('/home', 'CustomPageController@home')->name('home');
        Route::get('/privacy-policy', 'CustomPageController@privacy_policy')->name('privacy_policy');
        Route::get('/terms-conditions', 'CustomPageController@terms_conditions')->name('terms_conditions');
        Route::post('store', 'CustomPageController@storeCustomPage')->name('store');
        Route::post('store-homepage', 'CustomPageController@updateHomePage')->name('home.store');
    });


    //Faq
    Route::group(['prefix' => 'faq', 'as' => 'faq.'], function () {
        Route::get('/', 'FaqController@index')->name('index');
        Route::get('create', 'FaqController@create')->name('create');
        Route::post('store', 'FaqController@store')->name('store');
        Route::get('{id}/view', 'FaqController@view')->name('view');
        Route::get('{id}/edit', 'FaqController@edit')->name('edit');
        Route::post('{id}/update', 'FaqController@update')->name('update');
        Route::get('{id}/delete', 'FaqController@delete')->name('delete');
    });

    //Package
    Route::group(['prefix' => 'package', 'as' => 'package.'], function () {
        Route::get('/', 'PackageController@index')->name('index');
        Route::get('create', 'PackageController@create')->name('create');
        Route::post('store', 'PackageController@store')->name('store');
        Route::get('{id}/view', 'PackageController@view')->name('view');
        Route::get('{id}/edit', 'PackageController@edit')->name('edit');
        Route::post('{id}/update', 'PackageController@update')->name('update');
        Route::get('{id}/delete', 'PackageController@delete')->name('delete');
        // Route::get('{id}/delete', 'PackageController@delete')->name('delete');
        Route::get('{id}/getPaypal', 'PackageController@getPaypal')->name('getPaypal');
        Route::get('{id}/getFluterPlan', 'PackageController@getFluterPlan')->name('getFluterPlan');
    });

    // Account Setting
    // Route::get('account', ['as'=>'account','uses'=>'AccountController@account']);
    // Route::get('edit-account', ['as'=>'edit.account','uses'=>'AccountController@editAccount']);
    // Route::post('update-account', ['as'=>'update.account','uses'=>'AccountController@updateAccount']);
    // Route::get('change-password', ['as'=>'change.password','uses'=>'AccountController@changePassword']);
    // Route::post('update-password', ['as'=>'update.password','uses'=>'AccountController@updatePassword']);



    // Setting
    Route::get('pages', 'SettingsController@pages')->name('pages');
    Route::get('page/{home}', 'SettingsController@editHomePage')->name('edit.home');
    Route::post('page/{home}/update', 'SettingsController@updateHomePage')->name('update.home');

    Route::get('settings', 'SettingsController@general')->name('settings');
    Route::post('change-settings', 'SettingsController@changeSettings')->name('change.settings');
    Route::get('tax-setting', 'SettingsController@taxSetting')->name('tax.setting');
    Route::post('update-tex-setting', 'SettingsController@updateTaxSetting')->name('update.tax.setting');
    Route::post('update-email-setting', 'SettingsController@updateEmailSetting')->name('update.email.setting');
    Route::get('test-email', 'SettingsController@testEmail')->name('test.email');
    //cards
    //  Route::get('cards', 'CardController@index')->name('cards');
    // Route::get('card/trash', 'CardController@getTrashList')->name('card.trash');
    // Route::get('card/edit/{card_id}', 'CardController@edit')->name('card.edit');
    // Route::get('card/delete/{card_id}', 'CardController@delete')->name('card.delete');
    // Route::get('card/change-status/{card_id}', 'CardController@changeStatus')->name('card.change-status');
    // Route::get('card/active/{card_id}', 'CardController@activeCard')->name('card.active');

    // Plans
    // Route::get('plans', 'PlanController@plans')->name('plans');
    // Route::get('add-plan', 'PlanController@addPlan')->name('add.plan');
    // Route::post('save-plan', 'PlanController@savePlan')->name('save.plan');
    // Route::get('edit-plan/{id}', 'PlanController@editPlan')->name('edit.plan');
    // Route::get('shareable-update/{id}', 'PlanController@shareableUpdate')->name('shareable-update');
    // Route::post('update-plan', 'PlanController@updatePlan')->name('update.plan');
    // Route::get('plan/{id}/{period}/getstripe', 'PlanController@getstripe')->name('plan.getstripe');
    // Route::get('plan/{id}/{period}/getpaypal', 'PlanController@createPaypalPlan')->name('plan.getpaypal');
    // Route::get('delete-plan', 'PlanController@deletePlan')->name('delete.plan');

    // Roles
    Route::get('roles', 'RolesController@index')->name('roles.index');
    Route::get('roles/create', 'RolesController@create')->name('roles.create');
    Route::post('roles/store', 'RolesController@store')->name('roles.store');
    Route::get('roles/{id}/show', 'RolesController@show')->name('roles.show');
    Route::get('roles/{id}/edit', 'RolesController@edit')->name('roles.edit');
    Route::post('roles/{id}/update', 'RolesController@update')->name('roles.update');
    Route::delete('roles/{id}/destroy', 'RolesController@destroy')->name('roles.destroy');

    //Permissions
    Route::get('permissions', 'PermissionsController@index')->name('permissions.index');
    Route::get('permissions/create', 'PermissionsController@create')->name('permissions.create');
    Route::post('permissions/store', 'PermissionsController@store')->name('permissions.store');
    Route::get('permissions/{id}/show', 'PermissionsController@show')->name('permissions.show');
    Route::get('permissions/{id}/edit', 'PermissionsController@edit')->name('permissions.edit');
    Route::post('permissions/{id}/update', 'PermissionsController@update')->name('permissions.update');
    Route::post('permissions/{id}/destroy', 'PermissionsController@destroy')->name('permissions.destroy');

    // Admins
    Route::get('admins', 'UserController@adminIndex')->name('admins.index');
    Route::get('admins/create', 'UserController@adminCreate')->name('admins.create');
    Route::post('admins/store', 'UserController@adminStore')->name('admins.store');
    Route::get('admins/{id}/edit', 'UserController@adminEdit')->name('admins.edit');
    Route::post('admins/{id}/update', 'UserController@adminUpdate')->name('admins.update');
    Route::get('admins/{id}/view', 'UserController@adminView')->name('admins.view');
    Route::get('admins/{id}/destroy', 'UserController@adminDestroy')->name('admins.destroy');

    //Users
    Route::get('user/index/{type?}/{country?}', 'UserController@index')->name('user.index');
    Route::get('user/create', 'UserController@create')->name('user.create');
    Route::post('user/store', 'UserController@store')->name('user.store');
    Route::get('user/{id}/edit', 'UserController@edit')->name('user.edit');
    Route::post('user/{id}/update', 'UserController@update')->name('user.update');
    Route::get('user/{id}/view', 'UserController@view')->name('user.view');
    Route::post('user/{id}/password-update', 'UserController@passChange')->name('user.update.password');
    Route::get('user/{id}/destroy', 'UserController@destroy')->name('user.destroy');

    //institute
    Route::get('institute/index/{type?}', 'InstituteController@index')->name('institute.index');
    Route::get('institute/create', 'InstituteController@create')->name('institute.create');
    Route::post('institute/store', 'InstituteController@store')->name('institute.store');
    Route::get('institute/{id}/edit', 'InstituteController@edit')->name('institute.edit');
    Route::post('institute/{id}/update', 'InstituteController@update')->name('institute.update');
    Route::get('institute/{id}/view', 'InstituteController@view')->name('institute.view');
    Route::post('institute/{id}/password-update', 'InstituteController@passChange')->name('institute.update.password');
    Route::get('institute/{id}/destroy', 'InstituteController@destroy')->name('institute.destroy');
    Route::post('institute/{id}/assignBook', 'InstituteController@assignBook')->name('institute.assignBook');

    //book promo-code
    Route::group(['prefix' => 'book-promo', 'as' => 'book-promo.'], function () {
        Route::get('/', [PromoCodeBookController::class, 'index'])->name('index');
        Route::get('/create', [PromoCodeBookController::class, 'create'])->name('create');
        Route::post('/store', [PromoCodeBookController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PromoCodeBookController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [PromoCodeBookController::class, 'update'])->name('update');
        Route::get('/{id}/delete', [PromoCodeBookController::class, 'delete'])->name('delete');
    });
    //package promo-code
    Route::group(['prefix' => 'package-promo', 'as' => 'package-promo.'], function () {
        Route::get('/', [PromoCodePackageController::class, 'index'])->name('index');
        Route::get('/create', [PromoCodePackageController::class, 'create'])->name('create');
        Route::post('/store', [PromoCodePackageController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PromoCodePackageController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [PromoCodePackageController::class, 'update'])->name('update');
        Route::get('/{id}/delete', [PromoCodePackageController::class, 'delete'])->name('delete');
    });
    //package promo-code
    Route::group(['prefix' => 'push-notification', 'as' => 'push-notification.'], function () {
        Route::get('/', [PushNotificationController::class, 'index'])->name('index');
        Route::get('/create', [PushNotificationController::class, 'create'])->name('create');
        Route::post('/store', [PushNotificationController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PushNotificationController::class, 'edit'])->name('edit');
        Route::post('/{id}/update', [PushNotificationController::class, 'update'])->name('update');
        Route::get('/{id}/view', [PushNotificationController::class, 'view'])->name('view');
        Route::get('/{id}/send', [PushNotificationController::class, 'send'])->name('send');
        Route::get('/{id}/delete', [PushNotificationController::class, 'delete'])->name('delete');
    });
    // Route::resource('roles', RolesController::class);
    // Route::resource('permissions', PermissionsController::class);

    Route::get('edit-user/{id}', 'UserController@editUser')->name('edit.user');
    Route::post('update-user', 'UserController@updateUser')->name('update.user');
    Route::get('view-user/{id}', 'UserController@viewUser')->name('view.user');
    Route::get('change-user-plan/{id}', 'UserController@ChangeUserPlan')->name('change.user.plan');
    Route::post('update-user-plan', 'UserController@UpdateUserPlan')->name('update.user.plan');
    Route::get('update-status', 'UserController@updateStatus')->name('update.status');
    Route::get('active-user/{id}', 'UserController@activeStatus')->name('update.active-user');
    Route::get('delete-user', 'UserController@deleteUser')->name('delete.user');
    Route::get('login-as/{id}', 'UserController@authAs')->name('login-as.user');
    Route::get('user/trash-list', 'UserController@getTrashList')->name('user.trash-list');


    // Customers
    Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
        Route::get('/', 'CustomerController@index')->name('index');
        Route::get('create', 'CustomerController@create')->name('create');
        Route::post('store', 'CustomerController@store')->name('store');
        Route::get('{id}/edit', 'CustomerController@edit')->name('edit');
        Route::post('{id}/update', 'CustomerController@update')->name('update');
        Route::get('{id}/view', 'CustomerController@view')->name('view');
        Route::get('{id}/delete', 'CustomerController@delete')->name('delete');
    });

    // location
    Route::group(['prefix' => 'locations', 'as' => 'location.'], function () {
        Route::get('/', 'LocationController@index')->name('index');
        Route::get('create', 'LocationController@create')->name('create');
        Route::post('store', 'LocationController@store')->name('store');
        Route::get('edit/{slug}', 'LocationController@edit')->name('edit');
        Route::post('update/{slug}', 'LocationController@update')->name('update');
        Route::get('delete', 'LocationController@delete')->name('delete');
    });

    // investment
    Route::group(['prefix' => 'investments', 'as' => 'investment.'], function () {
        Route::get('/', 'InvestmentController@index')->name('index');
        Route::get('create', 'InvestmentController@create')->name('create');
        Route::post('store', 'InvestmentController@store')->name('store');
        Route::get('edit/{slug}', 'InvestmentController@edit')->name('edit');
        Route::post('update/{slug}', 'InvestmentController@update')->name('update');
        Route::get('delete', 'InvestmentController@delete')->name('delete');
    });

    // admin profile
    Route::get('profile', 'DashboardController@adminProfile')->name('profile');


    // franchises list
    Route::get('franchises', 'FranchisesController@index')->name('franchises.index');
    Route::get('franchise/create', 'FranchisesController@create')->name('franchises.create');
    Route::post('franchise/store', 'FranchisesController@store')->name('franchises.store');
    Route::get('franchise/{slug}/edit', 'FranchisesController@edit')->name('franchises.edit');
    Route::post('franchise/{slug}/update', 'FranchisesController@update')->name('franchises.update');
    Route::get('franchise/view/{slug}', 'FranchisesController@view')->name('franchises.view');
    Route::get('franchise/delete/{id}', 'FranchisesController@delete')->name('franchises.delete');


    //Category
    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::get('/', 'CategoryController@index')->name('index');
        Route::get('/create', 'CategoryController@create')->name('create');
        Route::post('/store', 'CategoryController@store')->name('store');
        Route::get('/{id}/edit', 'CategoryController@edit')->name('edit');
        Route::post('/update', 'CategoryController@update')->name('update');
        Route::get('/{id}/delete', 'CategoryController@delete')->name('delete');
    });

    //Ticket
    Route::group(['prefix' => 'ticket', 'as' => 'ticket.'], function () {
        Route::get('/', 'TicketController@index')->name('index');
        Route::get('/{id}/reply', 'TicketController@reply')->name('reply');
        Route::post('/store', 'TicketController@store')->name('store');
        Route::get('/{id}/close', 'TicketController@close')->name('close');
        Route::get('/{id}/reopen', 'TicketController@reopen')->name('reopen');
        Route::get('/{id}/delete', 'TicketController@delete')->name('delete');
    });

    //transactions
    Route::group(['prefix' => 'transaction', 'as' => 'transaction.'], function () {
        Route::get('/', 'TransactionController@index')->name('index');
        Route::get('/{id}/edit', 'TransactionController@edit')->name('edit');
        Route::get('/{id}/delete', 'TransactionController@delete')->name('delete');
    });

    //Payment-Request
    Route::group(['prefix' => 'payment-request', 'as' => 'payment.request.'], function () {
        Route::get('/', 'PaymentRequestController@index')->name('index');
        Route::get('/{id}', 'PaymentRequestController@statusChange')->name('status.change');
    });

    //SubCategory
    Route::group(['prefix' => 'subcategory', 'as' => 'subcategory.'], function () {
        Route::get('/', 'SubCategoryController@index')->name('index');
        Route::post('/store', 'SubCategoryController@store')->name('store');
        Route::get('/{id}/edit', 'SubCategoryController@edit')->name('edit');
        Route::post('/{id}/update', 'SubCategoryController@update')->name('update');
        Route::get('/{id}/delete', 'SubCategoryController@delete')->name('delete');
    });

    //Forum Questions
    Route::group(['prefix' => 'forum-questions', 'as' => 'forum.'], function () {
        Route::get('/', 'ForumController@index')->name('index');
        Route::get('/{id}/view', 'ForumController@view')->name('view');
        Route::get('/update-forum-status/{id}', 'ForumController@updateForumStatus')->name('updateForumStatus');
        Route::get('/{id}/delete', 'ForumController@destroy')->name('delete');
    });
    Route::get('user/reports', 'ForumController@report')->name('user.report');

    //Forum Category
    Route::group(['prefix' => 'forum-category', 'as' => 'forum.category.'], function () {
        Route::get('/', 'ForumCategoryController@index')->name('index');
        Route::get('/create', 'ForumCategoryController@create')->name('create');
        Route::post('/store', 'ForumCategoryController@store')->name('store');
        Route::get('/{id}/edit', 'ForumCategoryController@edit')->name('edit');
        Route::post('/{id}/update', 'ForumCategoryController@update')->name('update');
        Route::get('/{id}/delete', 'ForumCategoryController@delete')->name('delete');
    });

    //Forum Comment
    Route::group(['prefix' => 'forum-comments', 'as' => 'forum.comment.'], function () {
        Route::get('/', 'ForumCommentController@index')->name('index');
        Route::get('/{id}/view', 'ForumCommentController@view')->name('view');
        Route::get('/{id}/delete', 'ForumCommentController@destroy')->name('delete');
        Route::get('/update-forum-comment-status/{id}', 'ForumCommentController@updateForumCommentStatus')->name('updateForumCommentStatus');
    });


    //Blog
    Route::group(['prefix' => 'blog', 'as' => 'blog.'], function () {
        Route::get('/', 'BlogController@index')->name('index');
        Route::get('/create', 'BlogController@create')->name('create');
        Route::post('/store', 'BlogController@store')->name('store');
        Route::get('/{id}/edit', 'BlogController@edit')->name('edit');
        Route::post('/{id}/update', 'BlogController@update')->name('update');
        Route::get('/{id}/delete', 'BlogController@delete')->name('delete');
    });

    //Blog Category
    Route::group(['prefix' => 'blog-category', 'as' => 'blog.category.'], function () {
        Route::get('/', 'BlogCategoryController@index')->name('index');
        Route::get('create', 'BlogCategoryController@create')->name('create');
        Route::post('/store', 'BlogCategoryController@store')->name('store');
        Route::get('/{id}/edit', 'BlogCategoryController@edit')->name('edit');
        Route::post('/{id}/update', 'BlogCategoryController@update')->name('update');
        Route::get('/{id}/delete', 'BlogCategoryController@delete')->name('delete');
    });

    //Blog Comment
    Route::group(['prefix' => 'blog-comments', 'as' => 'blog.comment.'], function () {
        Route::get('/', 'BlogCommentController@index')->name('index');
        Route::get('/{id}/view', 'BlogCommentController@view')->name('view');
        Route::get('/{id}/delete', 'BlogCommentController@destroy')->name('delete');
        Route::get('/update-comment-status/{id}', 'BlogCommentController@updateCommentStatus')->name('updateCommentStatus');
    });

    //Blog Post
    Route::group(['prefix' => 'blog-post', 'as' => 'blog-post.'], function () {
        Route::get('/', 'BlogPostController@index')->name('index');
        Route::get('create', 'BlogPostController@create')->name('create');
        Route::post('store', 'BlogPostController@store')->name('store');
        Route::get('{id}/edit', 'BlogPostController@edit')->name('edit');
        Route::post('{id}/update', 'BlogPostController@update')->name('update');
        Route::get('{id}/view', 'BlogPostController@view')->name('view');
        Route::get('{id}/status/active', 'BlogPostController@activeStatus')->name('active');
        Route::get('{id}/status/inactive', 'BlogPostController@inactiveStatus')->name('inactive');
        Route::get('{id}/delete', 'BlogPostController@delete')->name('delete');
    });

    //Contact
    Route::group(['prefix' => 'contact', 'as' => 'contact.'], function () {
        Route::get('/', 'ContactController@index')->name('index');
        Route::get('create', 'ContactController@create')->name('create');
        Route::post('store', 'ContactController@store')->name('store');
        Route::get('{id}/edit', 'ContactController@edit')->name('edit');
        Route::post('{id}/update', 'ContactController@update')->name('update');
        Route::get('{id}/view', 'ContactController@view')->name('view');
        Route::get('{id}/delete', 'ContactController@delete')->name('delete');
    });


    //Country
    Route::group(['prefix' => 'country', 'as' => 'country.'], function () {
        Route::get('/', 'CountryController@index')->name('index');
        Route::get('create', 'CountryController@create')->name('create');
        Route::post('store', 'CountryController@store')->name('store');
        Route::get('{id}/edit', 'CountryController@edit')->name('edit');
        Route::post('{id}/update', 'CountryController@update')->name('update');
        // Route::get('{id}/view', 'CountryController@view')->name('view');
        Route::get('{id}/delete', 'CountryController@delete')->name('delete');
    });

    //Region
    Route::group(['prefix' => 'region', 'as' => 'region.'], function () {
        Route::get('/', 'RegionController@index')->name('index');
        Route::get('create', 'RegionController@create')->name('create');
        Route::post('store', 'RegionController@store')->name('store');
        Route::get('{id}/edit', 'RegionController@edit')->name('edit');
        Route::post('{id}/update', 'RegionController@update')->name('update');
        // Route::get('{id}/view', 'RegionController@view')->name('view');
        Route::get('{id}/delete', 'RegionController@delete')->name('delete');
    });

    //City
    Route::group(['prefix' => 'city', 'as' => 'city.'], function () {
        Route::get('/', 'CityController@index')->name('index');
        Route::get('create', 'CityController@create')->name('create');
        Route::post('store', 'CityController@store')->name('store');
        Route::get('{id}/edit', 'CityController@edit')->name('edit');
        Route::post('{id}/update', 'CityController@update')->name('update');
        Route::get('{id}/view', 'CityController@view')->name('view');
        Route::get('{id}/delete', 'CityController@delete')->name('delete');
        Route::get('country/region/{countryId?}', 'CityController@CountryWiseRegion')->name('countrywise.region');
    });




    //Book
    // Route::get('/book/create',[BookController::class,'create'])->name('book.create');
    Route::group(['prefix' => 'book', 'as' => 'book.'], function () {
        Route::get('index/{user?}/{books?}', 'BookController@index')->name('index');
        // doesnt work
        // Route::get('/', 'BookController@index')->name('index');
        Route::get('/create', 'BookController@create')->name('create');
        Route::post('/store', 'BookController@store')->name('store');
        Route::get('{id}/edit', 'BookController@edit')->name('edit');
        Route::post('update', 'BookController@update')->name('update');
        Route::get('{id}/view', 'BookController@view')->name('view');
        Route::get('{id}/analytic', 'BookController@analytic')->name('analytic');
        Route::any('analytic/{id}/status', 'BookController@analyticStatus')->name('analytic.status');
        Route::get('analytic/{id}/details', 'BookController@analyticDetails')->name('analytic.details');
        Route::get('analytic/{id}/delete', 'BookController@analyticDelete')->name('analytic.delete');
        Route::get('page/{id}/status', 'BookController@pageStatus')->name('page.status');
        Route::get('{id}/delete', 'BookController@delete')->name('delete');
        Route::get('{id}/marc-data', 'BookController@marcData')->name('marc');
        Route::get('{id}/month_book/{month_book}', 'BookController@month_book')->name('month_book');
        Route::post('borrowed/change', 'BookController@borrowedChange')->name('borrowedChange');
    });

    //User-book
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('/book', 'UserBookController@index')->name('book');
        Route::get('/favourite/book', 'UserBookController@favouriteBook')->name('favourite.book');
        Route::get('/borrow/book', 'UserBookController@borrowBook')->name('borrow.book');
        Route::get('/book/details', 'UserBookController@bookDeteils')->name('book.details');
    });

    // Club
    Route::group(['prefix' => 'club', 'as' => 'club.'], function () {
        Route::get('/', 'ClubController@index')->name('index');
        Route::get('/create', 'ClubController@create')->name('create');
        Route::post('/store', 'ClubController@store')->name('store');
        Route::get('{id}/status/{status?}', 'ClubController@changeStatus')->name('status.change');
        // Route::get('{id}/edit', 'ClubController@edit')->name('edit');
        // Route::post('update', 'ClubController@update')->name('update');
        Route::get('{id}/view', 'ClubController@view')->name('view');
        Route::get('/{id}/members', 'ClubController@members')->name('clubMembers');
        // Route::get('{id}/delete', 'ClubController@delete')->name('delete');
        Route::get('/info', 'ClubController@clubAbout')->name('about');
        Route::get('/community', 'ClubController@clubCommunity')->name('community');
        Route::get('/question/details', 'ClubController@clubDetails')->name('question.details');
        Route::get('/add/question', 'ClubController@addQuestion')->name('add.question');
    });

    Route::get('subscribers', [SubscribeController::class, 'index'])->name('subscriber');
    Route::get('unsubscribe/{email}', [SubscribeController::class, 'unsubscribe'])->name('subscriber.unsubscribe');
});
