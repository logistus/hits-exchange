<?php

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SurfController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\SquareBannerController;
use App\Http\Controllers\TextAdController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\SurfCodeController;
use App\Http\Controllers\StartPageController;
use App\Http\Controllers\SurferRewardController;
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

Route::get('/', function () {
  if (Auth::check()) {
    return redirect('dashboard');
  } else {
    $page = "Home";
    return view('home', compact('page'));
  }
});

Route::get('/login', function () {
  $page = "Login";
  return view('auth.login', compact('page'));
});

Route::get('/register', function () {
  $page = "Register";
  $countries = Country::orderBy('country_name', 'asc')->get();
  return view('auth.register', compact('page', 'countries'));
});

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
  return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Post requests
Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [LoginController::class, 'authenticate'])->middleware('throttle:6,1')->name('login');
Route::post('user/change-email', [UserController::class, 'change_email'])->middleware('auth');

Route::get('user/profile', [UserController::class, 'view_profile']);

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/dashboard', [UserController::class, 'dashboard']);
  Route::get('/surf', [SurfController::class, 'view']);
  Route::get('/surf_icons', [SurfController::class, 'surf_icons']);
  Route::get('/view_surf_icons', [SurfController::class, 'view_surf_icons']);
  Route::post('/validate_click/{id}', [SurfController::class, 'validate_click']);

  Route::get('/logout', [LoginController::class, 'logout']);

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

  Route::prefix('start_pages')->group(function () {
    Route::get('/', [StartPageController::class, 'index']);
  });

  Route::prefix('user')->group(function () {
    Route::post('profile', [UserController::class, 'save_profile']);
    Route::get('referrals', [UserController::class, 'referrals']);
    Route::post('password', [UserController::class, 'change_password']);
  });
});
