<?php

use App\Http\Controllers\AdminController;
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
use App\Http\Controllers\SquareBannerController;
use App\Http\Controllers\TextAdController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\SurfCodeController;
use App\Http\Controllers\StartPageController;
use App\Http\Controllers\SurferRewardController;
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

Route::middleware(['auth', 'verified', 'suspended'])->group(function () {
  Route::get('/dashboard', [UserController::class, 'dashboard']);
  Route::get('/surf', [SurfController::class, 'view']);
  Route::get('/surf_icons', [SurfController::class, 'surf_icons']);
  Route::get('/view_surf_icons', [SurfController::class, 'view_surf_icons']);
  Route::get('/promote', [UserController::class, 'promote']);
  Route::post('/validate_click/{id}', [SurfController::class, 'validate_click']);

  Route::get('/convert', [UserController::class, 'convert_view']);
  Route::post('/convert', [UserController::class, 'convert']);

  Route::get('/surfer_rewards', [SurferRewardController::class, 'index']);
  Route::post('/surfer_rewards', [SurferRewardController::class, 'store']);

  Route::get('surf_codes', [SurfCodeController::class, 'index']);
  Route::post('surf_codes', [SurfCodeController::class, 'store']);
  Route::get('surf_code_claimed/{id}', [SurfController::class, 'surf_code_claimed']);
  Route::get('start_page', [SurfController::class, 'start_page']);

  Route::get('start_page/delete/{id}', [StartPageController::class, 'destroy']);

  Route::prefix('buy')->group(function () {
    Route::get('start_page', [StartPageController::class, 'index_buy']);
    Route::post('start_page', [StartPageController::class, 'store_buy']);
    Route::get('login_spotlight', [LoginSpotlightController::class, 'index_buy']);
    Route::post('login_spotlight', [LoginSpotlightController::class, 'store_buy']);
  });

  Route::prefix('websites')->group(function () {
    Route::get('/', [WebsiteController::class, 'index']);
    Route::get('/auto_assign', [WebsiteController::class, 'auto_assign_view']);
    Route::get('/change_status/{id}', [WebsiteController::class, 'change_status']);
    Route::get('/delete/{id}', [WebsiteController::class, 'destroy']);
    Route::get('/{id}', [WebsiteController::class, 'show']);
    Route::put('/{id}', [WebsiteController::class, 'update_selected']);
    Route::get('/reset/{id}', [WebsiteController::class, 'website_reset']);

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
    Route::get('/click/{id}', [SquareBannerController::class, 'banner_click']);
    Route::get('/reset/{id}', [SquareBannerController::class, 'banner_reset']);

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
  Route::get('login_spotlights', [LoginSpotlightController::class, 'index']);

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
    Route::get('purchase_balance', [UserController::class, 'purchase_balance']);
    Route::get('purchase_balance/deposit', [UserController::class, 'purchase_balance_deposit']);
    Route::post('transfer_commissions', [UserController::class, 'transfer_commissions']);
  });
});

Route::middleware(['admin'])->group(function () {
  Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::prefix('members')->group(function () {
      Route::get('list', [AdminController::class, 'list_users']);
      Route::get('add', [AdminController::class, 'add_user_get']);
      Route::get('edit/{id}', [AdminController::class, 'edit_user_get']);
      Route::post('edit/{id}', [AdminController::class, 'edit_user_post']);
      Route::post('add', [AdminController::class, 'add_user_post']);
      Route::post('shortcuts', [AdminController::class, 'shortcuts']);
      Route::post('suspend', [AdminController::class, 'suspend']);
    });
  });
});
