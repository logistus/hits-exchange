<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SurfCodeController;
use App\Http\Controllers\SurfController;
use App\Http\Controllers\SurferRewardController;
use App\Http\Controllers\TextAdController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\StartPageController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

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
  $page = "Home";
  return view('home', compact('page'));
});

Route::get('/login', function () {
  $page = "Login";
  return view('auth.login', compact('page'));
});

Route::get('/register', function () {
  $page = "Register";
  return view('auth.register', compact('page'));
});

Route::get('/email/verify', function () {
  $page = "Verify Email";
  return view('auth.verify-email', compact('page'));
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
  $request->fulfill();
  return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Post requests
Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [LoginController::class, 'authenticate']);

Route::middleware(['auth'])->group(function () {
  Route::get('/surf', [SurfController::class, 'view']);
  Route::get('/surf_icon', [SurfController::class, 'create_selected_icon']);
  Route::get('/surf_icons', [SurfController::class, 'create_click_icons']);
  Route::post('/validate_click/{coordinate}', [SurfController::class, 'validate_click']);

  Route::get('/logout', [LoginController::class, 'logout']);

  Route::get('/convert', [UserController::class, 'convert_view']);
  Route::post('/convert', [UserController::class, 'convert']);
  Route::get('/referrals', [UserController::class, 'referrals']);

  Route::get('/surfer_rewards', [SurferRewardController::class, 'index']);
  Route::post('/surfer_rewards', [SurferRewardController::class, 'store']);

  Route::get('surf_codes', [SurfCodeController::class, 'index']);
  Route::post('surf_codes', [SurfCodeController::class, 'store']);
  Route::get('surf_code_claimed/{id}', [SurfController::class, 'surf_code_claimed']);
  Route::get('start_page', [SurfController::class, 'start_page']);

  Route::prefix('buy')->group(function () {
    Route::get('start_page', [StartPageController::class, 'index']);
    Route::post('start_page', [StartPageController::class, 'store']);
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
});
