<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
  public function store(Request $request)
  {
    $request->validate([
      "name" => "required",
      "username" => "required|unique:users",
      "email" => "required|email|unique:users",
      "password" => "required|min:8|confirmed",
      "tos" => "accepted",
    ]);

    $user = User::create($request->all());

    // Send email for email verification
    event(new Registered($user));

    // Log user in
    Auth::login($user);

    return redirect("/email/verify");
  }

  public function update_last_click($user_id)
  {
    return User::where("id", $user_id)->update(["last_click", time()]);
  }

  public function convert_view()
  {
    $page = "Conversions";
    return view("convert", compact('page'));
  }

  public function convert(Request $request)
  {
    $convert_from = $request->convert_from;
    $convert_to = $request->convert_to;
    (int) $convert_amount = $request->convert_amount;
    $credits_to_banner = $request->user()->type->credits_to_banner;
    $credits_to_text = $request->user()->type->credits_to_text;

    if ($convert_from == "banner_imps") {
      $text_imps_add = round($convert_amount * ($credits_to_text / $credits_to_banner));
      $request->user()->decrement("banner_imps", $convert_amount);
      $request->user()->increment("text_imps", $text_imps_add);
      $request->user()->save();
      return back();
    }

    if ($convert_from == "text_imps") {
      if ($convert_amount < ($credits_to_text / $credits_to_banner)) {
        return back()->withInput()->with("status", "Minimum text impression amount must be equal or greater than " . $credits_to_text / $credits_to_banner);
      } else {
        $banner_imps_add = round($convert_amount / ($credits_to_text / $credits_to_banner));
        $request->user()->decrement("text_imps", $convert_amount);
        $request->user()->increment("banner_imps", $banner_imps_add);
        $request->user()->save();
        return back();
      }
    }

    if ($convert_from == "credits") {
      if ($convert_to == "banner_imps") {
        $banner_imps_add = round($convert_amount * $credits_to_banner);
        $request->user()->decrement("credits", $convert_amount);
        $request->user()->increment("banner_imps", $banner_imps_add);
        $request->user()->save();
        return back();
      } else {
        $text_imps_add = round($convert_amount * $credits_to_text);
        $request->user()->decrement("credits", $convert_amount);
        $request->user()->increment("text_imps", $text_imps_add);
        $request->user()->save();
        return back();
      }
    }
  }

  public function referrals(Request $request)
  {
    $page = "Referrals";
    $sort_by = $request->query('sort_by') ? $request->query('sort_by') : 'join_date';
    if ($sort_by == "pages_surfed") {
      $referrals = $request->user()->referrals()->orderByDesc(DB::raw("`correct_clicks` + `wrong_clicks`"))->paginate(15)->withQueryString();
    } else {
      $referrals = $request->user()->referrals()->orderByDesc($sort_by)->paginate(15)->withQueryString();
    }
    return view("user.referrals", compact('referrals', 'page'));
  }
}
