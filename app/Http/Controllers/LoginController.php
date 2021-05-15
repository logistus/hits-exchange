<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
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

      return redirect()->intended('/');
    }

    return back()->withErrors(['invalid' => 'The provided credentials do not match our records.'])->withInput();
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
