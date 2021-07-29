<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Website;
use Illuminate\Http\Request;
use App\Models\LoginSpotlight;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class LoginSpotlightController extends Controller
{
  public function index(Request $request)
  {
    $login_spotlights = $request->user()->login_spotlights;
    $page = "Login Spotlights";
    return view('user.login_spotlights', compact('page', 'login_spotlights'));
  }

  public function index_buy(Request $request)
  {
    $page = "Buy Login Spotlight";
    $user_websites = Website::where('user_id', $request->user()->id)->where('status', 'Active')->get();
    $bought_dates = LoginSpotlight::select('dates')->where('status', 'Active')->get();
    $user_login_spotlights = $request->user()->login_spotlights;
    return view('buy.login_spotlight', compact('page', 'bought_dates', 'user_login_spotlights', 'user_websites'));
  }

  public function store_buy(Request $request)
  {
    $request->validate([
      "login_spotlight_user_website" => "required_if:new_login_spotlight_url,null",
      "new_login_spotlight_url" => "required_if:login_spotlight_user_website,0",
      "selected_dates" => "required"
    ]);

    if ($request->login_spotlight_user_website != 0 && $request->new_login_spotlight_url != "") {
      return back()->with("status", ["warning", "Either specify a new URL or select one from your websites. Not both."])->withInput();
    }

    if ($request->new_login_spotlight_url != "") {
      $isBanned = BannedUrlController::check_banned($request->new_login_spotlight_url);
      if ($isBanned) {
        return back()->with('status', ['warning', $isBanned]);
      }
      $login_spotlight_url = str_replace("http://", "https://", $request->new_login_spotlight_url);
    } else {
      $login_spotlight_url = Website::where('id', $request->login_spotlight_user_website)->value('url');
    }

    $selected_dates_array = explode(",", $request->selected_dates);
    $selected_dates = implode(", ", $selected_dates_array);


    $order = Order::create([
      "user_id" => Auth::id(),
      'order_type' => 'Login Spotlight',
      "order_item" => "Login Spotlight (" . $selected_dates . ")",
      "price" => count($selected_dates_array) * 2, // TODO: get start page price from database
      "status" => "Pending Payment"
    ]);

    LoginSpotlight::create([
      "user_id" => $request->user()->id,
      "order_id" => $order->id,
      "dates" => $selected_dates,
      "url" => $login_spotlight_url,
      "status" => "Pending Payment"
    ]);

    return redirect("user/orders");
  }

  public function destroy($id)
  {
    $login_spotlight = LoginSpotlight::findOrFail($id);
    $response = Gate::inspect("delete", $login_spotlight);
    if ($response->allowed()) {
      $login_spotlight->delete();
      Order::where('id', $login_spotlight->order_id)->delete();
      return back();
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }
}
