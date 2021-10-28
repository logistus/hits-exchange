<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\WebsiteController as AdminWebsiteController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\SquareBannerController as AdminSquareBannerController;
use App\Http\Controllers\Admin\TextAdController as AdminTextAdController;
use App\Http\Controllers\Admin\MemberTypeController as AdminMemberTypeController;
use App\Http\Controllers\Admin\SurferRewardController as AdminSurferRewardController;
use App\Http\Controllers\Admin\SurfCodeController as AdminSurfCodeController;
use App\Http\Controllers\Admin\SurfCodePrizeController as AdminSurfCodePrizeController;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\AdPriceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurfController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\LoginSpotlightController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PrivateMessageController;
use App\Http\Controllers\PurchaseBalanceController;
use App\Http\Controllers\SquareBannerController;
use App\Http\Controllers\TextAdController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\SurfCodeController;
use App\Http\Controllers\StartPageController;
use App\Http\Controllers\SurferRewardController;
use App\Http\Controllers\UserTypeController;
use App\Http\Controllers\WebsiteReportController;
use App\Http\Middleware\UpgradeCheck;
use App\Models\LoginSpotlight;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

// Get requests

Route::get('/', [UserController::class, 'home']);

Route::get('/ref/{id}', [UserController::class, 'ref_link']);

Route::get('/login', [LoginController::class, 'login']);
Route::post('/login', [LoginController::class, 'authenticate'])->middleware('throttle:6,1')->name('login');

Route::get('/logout', [LoginController::class, 'logout'])->middleware('auth');

Route::get('/register', [LoginController::class, 'register'])->middleware('guest');
Route::post('/register', [UserController::class, 'store'])->middleware('guest');


// Password Reset Routes
Route::get('forgot-password', [UserController::class, 'forgot_password'])
  ->middleware('guest')
  ->name('password.request');

Route::post('forgot-password', [UserController::class, 'send_password_reset_email'])
  ->middleware('guest')
  ->name('password.email');

Route::get('reset-password/{token}/{email}', [UserController::class, 'password_reset'])
  ->middleware('guest')->name('password.reset');

Route::post('reset-password', [UserController::class, 'password_reset_post'])
  ->middleware('guest')
  ->name('password.update');



// Email verification routes
Route::get('/email/verify', function (Request $request) {
  $page = "Verify Email Address";
  return view('auth.verify-email', compact('page'));
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
  $request->user()->sendEmailVerificationNotification();
  return back()->with('status', ['success', 'Verification email has been sent.']);
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
  $request->fulfill();
  User::where('id', Auth::user()->id)->update(['status' => 'Active']);
  return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('user/change-email', [UserController::class, 'change_email'])->middleware(['auth', 'suspended']);
Route::get('user/profile', [UserController::class, 'view_profile'])->middleware(['suspended']);
Route::get('suspended', [UserController::class, 'suspended']);

Route::middleware(['auth', 'verified', 'suspended', UpgradeCheck::class])->group(function () {
  Route::get('/dashboard', [UserController::class, 'dashboard']);
  Route::get('/upgrade', [UserController::class, 'upgrade']);
  Route::get('/surf', [SurfController::class, 'view']);
  Route::get('/frame_breaker', [SurfController::class, 'frame_breaker_detected']);
  Route::get('/surf_icons', [SurfController::class, 'surf_icons']);
  Route::get('/view_surf_icons', [SurfController::class, 'view_surf_icons']);
  Route::get('/promote', [UserController::class, 'promote']);
  Route::post('/validate_click/{id}', [SurfController::class, 'validate_click']);
  Route::get('report_website/{id}', [WebsiteReportController::class, 'index']);
  Route::post('report_website/{id}', [WebsiteReportController::class, 'store']);

  Route::get('/convert', [UserController::class, 'convert_view']);
  Route::post('/convert', [UserController::class, 'convert']);

  Route::get('/surfer_rewards', [SurferRewardController::class, 'index']);
  Route::post('/surfer_rewards', [SurferRewardController::class, 'store']);

  Route::get('surf_codes', [SurfCodeController::class, 'index']);
  Route::post('surf_codes', [SurfCodeController::class, 'store']);
  Route::get('surf_code_claimed/{id}', [SurfController::class, 'surf_code_claimed']);
  Route::get('start_page', [SurfController::class, 'start_page']);
  Route::get('prize_page', [SurfController::class, 'prize_page']);
  Route::post('claim_surf_prize', [SurfController::class, 'claim_surf_prize']);
  Route::get('login_spotlight', [SurfController::class, 'login_spotlight']);
  Route::post('login_spotlight', [SurfController::class, 'login_spotlight_prize']);
  Route::get('signup_bonus_claimed/{id}', [SurfController::class, 'signup_bonus_claimed']);

  Route::prefix('buy')->group(function () {
    Route::get('start_page', [StartPageController::class, 'index_buy']);
    Route::post('start_page', [StartPageController::class, 'store_buy']);
    Route::get('login_spotlight', [LoginSpotlightController::class, 'index_buy']);
    Route::post('login_spotlight', [LoginSpotlightController::class, 'store_buy']);
    Route::post('upgrade/{type_id}/{price_id}', [UserTypeController::class, 'store_buy']);
    Route::get('credits', [AdPriceController::class, 'index']);
    Route::post('credits/{id}', [AdPriceController::class, 'store']);
    Route::get('ipn', [OrderController::class, 'ipn']);
  });

  Route::prefix('websites')->group(function () {
    Route::get('/', [WebsiteController::class, 'index']);
    Route::get('/auto_assign', [WebsiteController::class, 'auto_assign_view']);
    Route::get('/change_status/{id}', [WebsiteController::class, 'change_status']);
    Route::get('/delete/{id}', [WebsiteController::class, 'destroy']);
    Route::get('/{id}', [WebsiteController::class, 'show']);
    Route::put('/{id}', [WebsiteController::class, 'update_selected']);
    Route::get('/reset/{id}', [WebsiteController::class, 'website_reset']);
    Route::get('/check_website/{id}', [WebsiteController::class, 'website_check']);

    Route::post('/approve/{id}', [WebsiteController::class, 'website_approve']);
    Route::post('/', [WebsiteController::class, 'store']);
    Route::post('/update', [WebsiteController::class, 'update']);
    Route::post('/auto_assign', [WebsiteController::class, 'auto_assign']);
  });
  Route::prefix('banners')->group(function () {
    Route::get('/', [BannerController::class, 'index']);
    Route::get('/click/{id}', [BannerController::class, 'banner_click']);
    Route::get('/reset/{id}', [BannerController::class, 'banner_reset']);

    Route::get('/change_status/{id}', [BannerController::class, 'change_status']);
    Route::get('/delete/{id}', [BannerController::class, 'destroy']);
    Route::get('/{id}', [BannerController::class, 'show']);
    Route::put('/{id}', [BannerController::class, 'update_selected']);

    Route::post('/', [BannerController::class, 'store']);
    Route::post('/update', [BannerController::class, 'update']);
  });
  Route::prefix('square_banners')->group(function () {
    Route::get('/', [SquareBannerController::class, 'index']);
    Route::get('/click/{id}', [SquareBannerController::class, 'square_banner_click']);
    Route::get('/reset/{id}', [SquareBannerController::class, 'square_banner_reset']);

    Route::get('/change_status/{id}', [SquareBannerController::class, 'change_status']);
    Route::get('/delete/{id}', [SquareBannerController::class, 'destroy']);
    Route::get('/{id}', [SquareBannerController::class, 'show']);
    Route::put('/{id}', [SquareBannerController::class, 'update_selected']);

    Route::post('/', [SquareBannerController::class, 'store']);
    Route::post('/update', [SquareBannerController::class, 'update']);
  });
  Route::prefix('texts')->group(function () {
    Route::get('/', [TextAdController::class, 'index']);
    Route::get('/click/{text_id}', [TextAdController::class, 'text_click']);
    Route::get('/reset/{id}', [TextAdController::class, 'text_reset']);
    Route::get('/change_status/{id}', [TextAdController::class, 'change_status']);
    Route::get('/delete/{id}', [TextAdController::class, 'destroy']);
    Route::get('/{id}', [TextAdController::class, 'show']);
    Route::put('/{id}', [TextAdController::class, 'update_selected']);

    Route::post('/', [TextAdController::class, 'store']);
    Route::post('/update', [TextAdController::class, 'update']);
  });

  Route::get('start_pages', [StartPageController::class, 'index']);
  Route::post('start_pages/delete/{id}', [StartPageController::class, 'destroy']);
  Route::get('login_spotlights', [LoginSpotlightController::class, 'index']);
  Route::post('login_spotlights/delete/{id}', [LoginSpotlightController::class, 'destroy']);

  Route::prefix('private_messages')->group(function () {
    Route::get('/', [PrivateMessageController::class, 'inbox']);
    Route::get('/sent', [PrivateMessageController::class, 'sent']);
    Route::get('/trash', [PrivateMessageController::class, 'trash']);
    Route::get('/compose', [PrivateMessageController::class, 'create']);
    Route::get('/compose_directly/{id}', [PrivateMessageController::class, 'create_with_id']);
    Route::get('/empty_trash', [PrivateMessageController::class, 'empty_trash']);
    Route::get('/move_inbox/{id}', [PrivateMessageController::class, 'move_inbox']);
    Route::get('/move_trash/{id}', [PrivateMessageController::class, 'move_trash']);
    Route::get('/report/{id}', [PrivateMessageController::class, 'report']);
    Route::get('/delete_from_sender/{id}', [PrivateMessageController::class, 'delete_from_sender']);
    Route::get('/delete/{id}', [PrivateMessageController::class, 'destroy']);
    Route::get('/reply/{id}', [PrivateMessageController::class, 'reply']);
    Route::get('/{id}', [PrivateMessageController::class, 'show']);

    Route::post('/', [PrivateMessageController::class, 'store']);
    Route::post('/update', [PrivateMessageController::class, 'update']);
    Route::post('/reply/{id}', [PrivateMessageController::class, 'send_reply']);
  });

  Route::prefix('user')->group(function () {
    Route::get('referrals', [UserController::class, 'referrals']);
    Route::get('transfer_credits/{id}', [UserController::class, 'transfer_credits']);
    Route::post('transfer_credits/{id}', [UserController::class, 'transfer_credits_post']);
    Route::prefix('orders')->group(function () {
      Route::get('/', [OrderController::class, 'index']);
      Route::post('/delete/{id}', [OrderController::class, 'destroy']);
      Route::post('/pay_with_purchase_balance/{id}', [OrderController::class, 'pay_with_purchase_balance']);
    });
    Route::post('password', [UserController::class, 'change_password']);
    Route::post('profile', [UserController::class, 'save_profile']);
    Route::get('commissions', [UserController::class, 'commissions']);

    Route::get('transfer_commissions', [PurchaseBalanceController::class, 'transfer_commissions']);
    Route::post('transfer_commissions', [PurchaseBalanceController::class, 'transfer_commissions_post']);
    Route::get('purchase_balance', [PurchaseBalanceController::class, 'index']);
    Route::get('purchase_balance/create', [PurchaseBalanceController::class, 'create']);
    Route::post('purchase_balance/create', [PurchaseBalanceController::class, 'store']);
    Route::get('purchase_balance/deposit/{id}', [PurchaseBalanceController::class, 'deposit']);
    Route::get('purchase_balance/delete/{id}', [PurchaseBalanceController::class, 'destroy']);
  });
});

// Admin routes
Route::middleware(['admin'])->group(function () {
  Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::prefix('members')->group(function () {
      Route::get('list', [AdminUserController::class, 'list_users']);
      Route::get('add', [AdminUserController::class, 'add_user_get']);
      Route::get('edit/{id}', [AdminUserController::class, 'edit_user_get']);
      Route::post('edit/{id}', [AdminUserController::class, 'edit_user_post']);
      Route::post('add', [AdminUserController::class, 'add_user_post']);
      Route::post('actions', [AdminUserController::class, 'actions_members']);
      Route::post('suspend', [AdminUserController::class, 'suspend']);
    });
    Route::prefix('member_types')->group(function () {
      Route::get('/', [AdminMemberTypeController::class, 'index']);
      Route::get('/create', [AdminMemberTypeController::class, 'create']);
      Route::post('/', [AdminMemberTypeController::class, 'store']);
      Route::get('/delete/{id}', [AdminMemberTypeController::class, 'destroy']);
      Route::get('/edit/{id}', [AdminMemberTypeController::class, 'edit']);
      Route::put('/{id}', [AdminMemberTypeController::class, 'update']);
    });
    Route::prefix('websites')->group(function () {
      Route::get('list', [AdminWebsiteController::class, 'list_websites']);
      Route::get('add', [AdminWebsiteController::class, 'add_website_get']);
      Route::get('edit/{id}', [AdminWebsiteController::class, 'edit_website_get']);
      Route::get('pause/{id}', [AdminWebsiteController::class, 'pause_website']);
      Route::get('activate/{id}', [AdminWebsiteController::class, 'activate_website']);
      Route::get('suspend/{id}', [AdminWebsiteController::class, 'suspend_website']);
      Route::get('delete/{id}', [AdminWebsiteController::class, 'destroy']);
      Route::post('edit/{id}', [AdminWebsiteController::class, 'edit_website_post']);
      Route::post('add', [AdminWebsiteController::class, 'add_website_post']);
      Route::post('actions', [AdminWebsiteController::class, 'actions_websites']);
    });
    Route::prefix('banners')->group(function () {
      Route::get('list', [AdminBannerController::class, 'list_banners']);
      Route::get('add', [AdminBannerController::class, 'add_banner_get']);
      Route::get('edit/{id}', [AdminBannerController::class, 'edit_banner_get']);
      Route::get('pause/{id}', [AdminBannerController::class, 'pause_banner']);
      Route::get('activate/{id}', [AdminBannerController::class, 'activate_banner']);
      Route::get('suspend/{id}', [AdminBannerController::class, 'suspend_banner']);
      Route::get('delete/{id}', [AdminBannerController::class, 'destroy']);
      Route::post('edit/{id}', [AdminBannerController::class, 'edit_banner_post']);
      Route::post('add', [AdminBannerController::class, 'add_banner_post']);
      Route::post('actions', [AdminBannerController::class, 'actions_banners']);
    });
    Route::prefix('square_banners')->group(function () {
      Route::get('list', [AdminSquareBannerController::class, 'list_square_banners']);
      Route::get('add', [AdminSquareBannerController::class, 'add_square_banner_get']);
      Route::get('edit/{id}', [AdminSquareBannerController::class, 'edit_square_banner_get']);
      Route::get('pause/{id}', [AdminSquareBannerController::class, 'pause_square_banner']);
      Route::get('activate/{id}', [AdminSquareBannerController::class, 'activate_square_banner']);
      Route::get('suspend/{id}', [AdminSquareBannerController::class, 'suspend_square_banner']);
      Route::get('delete/{id}', [AdminSquareBannerController::class, 'destroy']);
      Route::post('edit/{id}', [AdminSquareBannerController::class, 'edit_square_banner_post']);
      Route::post('add', [AdminSquareBannerController::class, 'add_square_banner_post']);
      Route::post('actions', [AdminSquareBannerController::class, 'actions_square_banners']);
    });
    Route::prefix('text_ads')->group(function () {
      Route::get('list', [AdminTextAdController::class, 'list_text_ads']);
      Route::get('add', [AdminTextAdController::class, 'add_text_ad_get']);
      Route::get('edit/{id}', [AdminTextAdController::class, 'edit_text_ad_get']);
      Route::get('pause/{id}', [AdminTextAdController::class, 'pause_text_ad']);
      Route::get('activate/{id}', [AdminTextAdController::class, 'activate_text_ad']);
      Route::get('suspend/{id}', [AdminTextAdController::class, 'suspend_text_ad']);
      Route::get('delete/{id}', [AdminTextAdController::class, 'destroy']);
      Route::post('edit/{id}', [AdminTextAdController::class, 'edit_text_ad_post']);
      Route::post('add', [AdminTextAdController::class, 'add_text_ad_post']);
      Route::post('actions', [AdminTextAdController::class, 'actions_text_ads']);
    });

    Route::prefix('surfer_rewards')->group(function () {
      Route::get('/', [AdminSurferRewardController::class, 'list']);
      Route::get('create', [AdminSurferRewardController::class, 'create']);
      Route::post('/', [AdminSurferRewardController::class, 'store']);
      Route::get('edit/{id}', [AdminSurferRewardController::class, 'edit']);
      Route::put('/{id}', [AdminSurferRewardController::class, 'update']);
      Route::get('delete/{id}', [AdminSurferRewardController::class, 'destroy']);
    });

    Route::prefix('surf_codes')->group(function () {
      Route::get('/', [AdminSurfCodeController::class, 'list']);
      Route::get('create', [AdminSurfCodeController::class, 'create']);
      Route::post('/', [AdminSurfCodeController::class, 'store']);
      Route::get('edit/{id}', [AdminSurfCodeController::class, 'edit']);
      Route::put('/{id}', [AdminSurfCodeController::class, 'update']);
      Route::get('delete/{id}', [AdminSurfCodeController::class, 'destroy']);
      Route::get('prizes/delete/{id}', [AdminSurfCodePrizeController::class, 'destroy']);
      Route::post('prizes', [AdminSurfCodePrizeController::class, 'store']);
    });
  });
});
