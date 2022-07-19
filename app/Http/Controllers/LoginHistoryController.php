<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginHistoryController extends Controller
{
  public function index(Request $request)
  {
    $page = "Login History";
    $logins = $request->user()->login_histories()->paginate(15);
    return view('user.login_history', compact('page', 'logins'));
  }
}
