<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Country;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use App\Models\LoginSpotlight;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
  public function login()
  {
    $page = "Login";
    return view('auth.login', compact('page'));
  }

  public function register()
  {
    $page = "Register";
    $countries = Country::orderBy('name', 'asc')->get();
    return view('auth.register', compact('page', 'countries'));
  }

  public function authenticate(Request $request)
  {
    $request->validate([
      'username' => 'required',
      'password' => 'required'
    ]);

    $credentials = $request->only('username', 'password');


    if (Auth::attempt($credentials, $request->input('remember'))) {
      $request->session()->regenerate();
      $request->user()->last_login = now();
      $request->user()->save();

      LoginHistory::create([
        'user_id' => $request->user()->id,
        'datetime' => now(),
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'status' => 1
      ]);

      // check login spotlight

      $login_spotlight_check_url = LoginSpotlight::where('status', 'Active')->where('dates', "LIKE",  "%" . date('Y-m-d') . "%")->value('url');
      $login_spotlight_check_user = $request->user()->login_spotlight_viewed;

      if ($login_spotlight_check_url && !$login_spotlight_check_user) {
        return redirect('login_spotlight');
      }

      return redirect()->intended('/dashboard');
    }
    // check given username exists in database
    $logging_user = User::where('username', $request->input('username'))->limit(1)->value('id');
    // if exists, save unsucessful login to login history
    if ($logging_user) {
      LoginHistory::create([
        'user_id' => $logging_user,
        'datetime' => now(),
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'status' => 0
      ]);
    }
    return back()->with('status', ['danger', 'The provided credentials do not match our records.'])->withInput();
  }

  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
  }

  public function username()
  {
    return 'username';
  }
}
