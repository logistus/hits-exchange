<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SplashPage;
use Illuminate\Support\Facades\Cookie;

class SplashPageController extends Controller
{
  public function index($splash_id, $username)
  {
    $user = User::where('username', $username)->get()->first();
    $splash = SplashPage::where('id', $splash_id)->get()->first();

    if ($user) {
      Cookie::queue("hits_exchange_ref", $username, 60 * 24 * 30);
      if ($splash) {
        $html_codes = SplashPage::where('id', $splash_id)->value('html_codes');
        return view('splash_page', compact('html_codes', 'user'));
      } else {
        return redirect(config('app.url') . '/ref/' . $username);
      }
    } else {
      return redirect(config('app.url'));
    }
  }
}
