<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Country;
use App\Models\UserType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\PasswordChanged;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Cookie;

class UserController extends Controller
{
  public function store(Request $request)
  {
    $request->validate([
      "name" => "required|string",
      "surname" => "required|string",
      "username" => "required|unique:users",
      "email" => "required|email|unique:users",
      "country" => "required",
      "password" => "required|min:8|confirmed",
      "tos" => "accepted",
    ]);
    $user = User::create($request->only('name', 'username', 'email', 'password'));
    $user->surname = $request->surname;
    $user->country = $request->country;
    $user->last_login = now();
    $user->save();

    // Send email for email verification
    event(new Registered($user));

    // Log user in
    Auth::login($user);

    return redirect("/email/verify");
  }

  public function dashboard()
  {
    $page = "Dashboard";
    return view("dashboard", compact("page"));
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

  public function view_profile(Request $request)
  {
    $page = "Edit Profile";
    $countries = Country::orderBy('country_name', 'asc')->get();
    return view('user.profile', compact('page', 'countries'));
  }

  public function save_profile(Request $request)
  {
    $request->validate([
      "email" => "required|email",
      "name" => "required|string",
      "surname" => "required|string",
      "username" => "required|string"
    ]);

    $status = null;

    if ($request->user()->email != $request->email) {
      $email_check = User::where('email', $request->email)->value('id');
      if ($email_check) {
        $status = "This email has already been taken.";
      } else {
        $request->user()->email = $request->email;
        $request->user()->email_verified_at = NULL;
      }
    }

    if ($request->user()->username != $request->username) {
      $username_check = User::where('username', $request->username)->value('id');
      if ($username_check) {
        $status = "This username has already been taken.";
      } else {
        $request->user()->username = $request->username;
      }
    }

    $request->user()->name = $request->name;
    $request->user()->surname = $request->surname;
    $request->user()->country = $request->country;
    $request->user()->referral_notification = $request->input('referral_notification') ? 1 : 0;
    $request->user()->commission_notification = $request->input('commission_notification') ? 1 : 0;
    $request->user()->pm_notification = $request->input('pm_notification') ? 1 : 0;

    if (
      $request->user()->isDirty('name') ||
      $request->user()->isDirty('surname') ||
      $request->user()->isDirty('country') ||
      $request->user()->isDirty('username') ||
      $request->user()->isDirty('referral_notification') ||
      $request->user()->isDirty('commission_notification') ||
      $request->user()->isDirty('pm_notification')
    ) {
      $request->user()->save();
      return back()->with("status", ["success", "Profile updated."]);
    } else if ($request->user()->isDirty('email')) {
      $request->user()->save();
      $request->user()->sendEmailVerificationNotification();
      return back()->with("status", ["success", "Profile updated."]);
    } else {
      return $status ? back()->with("status", ["warning", $status]) : back();
    }
  }

  public function change_email(Request $request)
  {
    $request->validate([
      'email' => 'required|email'
    ]);

    $email_check = User::where('email', $request->email)->value('id');
    if ($email_check) {
      return back()->with("status", ["warning", "This email has already been taken."]);
    } else {
      $request->user()->email = $request->email;
      $request->user()->email_verified_at = NULL;
      $request->user()->save();
      $request->user()->sendEmailVerificationNotification();
      return redirect('email/verify');
    }
  }

  public function change_password(Request $request)
  {
    $request->validate([
      "current_password" => "required",
      "new_password" => "required|min:8|confirmed"
    ]);

    // check current password
    if (Hash::check($request->current_password, $request->user()->password)) {
      // password correct, change password
      $request->user()->password = $request->new_password; // did not hashed because User model hashes password before saving
      $request->user()->save();
      $request->session()->passwordConfirmed();
      // send an email to user
      $request->user()->notify(new PasswordChanged($request->user()));
      return back()->with("status", ["success", "Your password has been changed."]);
    } else {
      return back()->with("status", ["warning", "Invalid current password."]);
    }
  }

  public function forgot_password()
  {
    $page = "Forgot Password";
    return view('auth.forgot-password', compact('page'));
  }

  public function send_password_reset_email(Request $request)
  {
    $request->validate(['email' => 'required|email']);

    Password::sendResetLink(
      $request->only('email')
    );

    return back()->with('status', ['success', 'If there is an account with this email address, we have emailed your password reset link!']);
  }

  public function password_reset($token, $email)
  {
    $page = "Password Reset";
    return view('auth.reset-password', compact('token', 'email', 'page'));
  }

  public function password_reset_post(Request $request)
  {
    $request->validate([
      'token' => 'required',
      'email' => 'required|email',
      'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
      $request->only('email', 'password', 'password_confirmation', 'token'),
      function ($user, $password) {
        $user->forceFill([
          'password' => $password
        ])->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));
      }
    );

    return $status === Password::PASSWORD_RESET
      ? redirect()->route('login')->with('status', ['success', __($status)])
      : back()->with('status', ['warning', __($status)]);
  }

  public function list_users(Request $request)
  {
    if (!$request->cookie('members_list_per_page') && $request->query('per_page') == "") {
      Cookie::queue('members_list_per_page', 25);
    }
    if ((!$request->cookie('members_list_per_page') && $request->query('per_page') != "") || ($request->cookie('members_list_per_page') && $request->query('per_page') != "")) {
      Cookie::queue('members_list_per_page', $request->query('per_page'));
    }
    $per_page =  $request->query('per_page') ? $request->query('per_page') : $request->cookie('members_list_per_page');

    $sort = $request->query('sort') ? $request->query('sort') : "desc";
    $sort_by = $request->query('sort_by') ? $request->query('sort_by') : 'join_date';
    $user_types = UserType::all();

    $filterUsername = $request->query('filterByUsername');
    $filterEmail = $request->query('filterByEmail');
    $filterUserType = $request->query('filterByUserType');

    $users = User::when($filterUsername, function ($query, $filterUsername) {
      return $query->where('username', $filterUsername);
    })->when($filterEmail, function ($query, $filterEmail) {
      return $query->where('email', $filterEmail);
    })->when($filterUserType, function ($query, $filterUserType) {
      return $query->where('user_type', $filterUserType);
    })->orderBy($sort_by, $sort)->paginate($per_page)->withQueryString();

    return view('admin.members.list', compact('users', 'per_page', 'user_types'));
  }
}
