<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Country;
use App\Models\SurfCode;
use App\Models\UserType;
use App\Models\SplashPage;
use App\Models\SignupBonus;
use Illuminate\Support\Str;
use App\Models\LoginHistory;
use App\Models\PromoTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\PasswordChanged;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Notifications\ReferralNotification;

class UserController extends Controller
{
  public function home()
  {
    $page = "Home";
    return view('home', compact('page'));
  }

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

    // Send email for email verification
    event(new Registered($user));

    // Check if user has upline, if there is and he/she wants an email, send it
    if ($request->cookie('hits_exchange_ref')) {
      $upline =  User::where('username', request()->cookie('hits_exchange_ref'))->get()->first();
      if ($upline) {
        $user->upline = $upline->id;
        if ($upline->referral_notification)
          $upline->notify(new ReferralNotification($upline));
      }
    }

    // Check/save promo tracker
    if ($request->cookie('hits_exchange_tracker')) {
      $user->tracker = $request->cookie('hits_exchange_tracker');
    }

    $user->save();


    // Log user in
    Auth::login($user);

    LoginHistory::create([
      'user_id' => Auth::id(),
      'datetime' => now(),
      'ip_address' => $_SERVER['REMOTE_ADDR'],
      'status' => 1
    ]);

    return redirect("/email/verify");
  }

  public function dashboard()
  {
    $page = "Dashboard";
    $signup_bonuses = SignupBonus::all();
    $surf_codes = SurfCode::where('confirmed', 1)->where('valid_from', date("Y-m-d"))->get()->first();
    return view("dashboard", compact("page", "signup_bonuses", "surf_codes"));
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
    $credits_to_square_banner = $request->user()->type->credits_to_square_banner;
    $credits_to_text = $request->user()->type->credits_to_text;

    if ($convert_from == "banner_imps") {
      if ($convert_amount > $request->user()->banner_imps) {
        return back()->withInput()->with("status", ["warning", "Maximum banner impression can't be greater than " . $request->user()->banner_imps]);
      } else {
        $text_imps_add = round($convert_amount * ($credits_to_text / $credits_to_banner));
        $request->user()->decrement("banner_imps", $convert_amount);
        $request->user()->increment("text_imps", $text_imps_add);
        $request->user()->save();
        return back();
      }
    }

    if ($convert_from == "text_imps") {
      if ($convert_amount > $request->user()->text_imps) {
        return back()->withInput()->with("status", ["warning", "Maximum text impression can't be greater than " . $request->user()->text_imps]);
      }
      if ($convert_to == "banner_imps") {
        if ($convert_amount < ($credits_to_text / $credits_to_banner)) {
          return back()->withInput()->with("status", ["warning", "Minimum text impression amount must be equal or greater than " . $credits_to_text / $credits_to_banner]);
        }
        $banner_imps_add = round($convert_amount / ($credits_to_text / $credits_to_banner));
        $request->user()->decrement("text_imps", $convert_amount);
        $request->user()->increment("banner_imps", $banner_imps_add);
        $request->user()->save();
        return back();
      } else {
        if ($convert_amount < ($credits_to_text / $credits_to_square_banner)) {
          return back()->withInput()->with("status", ["warning", "Minimum text impression amount must be equal or greater than " . $credits_to_text / $credits_to_square_banner]);
        }
        $square_banner_imps_add = round($convert_amount / ($credits_to_text / $credits_to_square_banner));
        $request->user()->decrement("text_imps", $convert_amount);
        $request->user()->increment("square_banner_imps", $square_banner_imps_add);
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
      } else if ($convert_to == "square_banner_imps") {
        $square_banner_imps_add = round($convert_amount * $credits_to_square_banner);
        $request->user()->decrement("credits", $convert_amount);
        $request->user()->increment("square_banner_imps", $square_banner_imps_add);
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
    $sort = $request->query("sort") ? $request->query("sort") : "desc";

    $filterUsername = $request->query('filterByUsername');
    $filterByUserType = $request->query('filterByUserType');
    $filterByStatus = $request->query('filterByStatus');
    $filterByTracker = $request->query('filterByTracker');

    if ($sort_by == '' || $sort_by == 'username' || $sort_by == 'join_date' || $sort_by == 'last_login' || $sort_by == 'user_type' || $sort_by == 'pages_surfed' || $sort_by == 'status' || $sort_by == 'total_purchased') {
      if ($sort_by == "pages_surfed") {
        $referrals = $request->user()->referrals()
          ->select('id', 'username', 'user_type', 'status', 'last_login', 'join_date', 'total_purchased', 'correct_clicks', 'wrong_clicks')
          ->when($filterUsername, function ($query, $filterUsername) {
            return $query->where('username', $filterUsername);
          })
          ->when($filterByTracker, function ($query, $filterByTracker) {
            return $query->where('tracker', $filterByTracker);
          })
          ->when($filterByUserType, function ($query, $filterByUserType) {
            return $query->where('user_type', $filterByUserType);
          })
          ->when($filterByStatus, function ($query, $filterByStatus) {
            return $query->where('status', $filterByStatus);
          })
          ->orderBy(DB::raw("`correct_clicks` + `wrong_clicks`"), $sort)
          ->paginate(15)
          ->withQueryString();
      }
      /* I might need this later

    else if ($sort_by == "total_purchased") {
      $referrals = $request->user()->referrals()
        ->select('users.id', 'username', 'user_type', 'users.status', 'last_login', 'join_date', 'correct_clicks', 'wrong_clicks')
        ->selectRaw('SUM(orders.price) AS total_purchased')
        ->join('orders', function($join) {
            $join->on( 'users.id', '=', 'orders.user_id')->where
        })
        ->orderByRaw('SUM(orders.price) DESC')
        ->groupBy('orders.user_id')
        ->whereExists(function ($query) {
          $query->select(DB::raw(1))
            ->from('orders')
            ->whereColumn('orders.user_id', 'users.id');
        })
        ->paginate(15)
        ->withQueryString();
      */ else {
        $referrals = $request->user()->referrals()
          ->select('id', 'username', 'user_type', 'status', 'last_login', 'join_date', 'total_purchased', 'correct_clicks', 'wrong_clicks')
          ->when($filterUsername, function ($query, $filterUsername) {
            return $query->where('username', $filterUsername);
          })
          ->when($filterByTracker, function ($query, $filterByTracker) {
            return $query->where('tracker', $filterByTracker);
          })
          ->when($filterByUserType, function ($query, $filterByUserType) {
            return $query->where('user_type', $filterByUserType);
          })
          ->when($filterByStatus, function ($query, $filterByStatus) {
            return $query->where('status', $filterByStatus);
          })
          ->orderBy($sort_by, $sort)
          ->paginate(15)
          ->withQueryString();
      }
      $user_types = UserType::all();
      return view("user.referrals", compact('referrals', 'page', 'user_types'));
    } else {
      $referrals = null;
      return back()->with("status", ["warning", "Invalid sorting type."]);
    }
  }

  public function view_profile(Request $request)
  {
    $page = "Edit Profile";
    $countries = Country::orderBy('name', 'asc')->get();
    return view('user.profile', compact('page', 'countries'));
  }

  public function save_profile(Request $request)
  {
    //return $request->all();
    $request->validate([
      "email" => "required|email",
      "name" => "required|string",
      "surname" => "required|string",
      "username" => "required|string",
    ]);

    $status = null;

    if ($request->user()->email != $request->email) {
      $email_check = User::where('email', $request->email)->value('id');
      if ($email_check) {
        $status = "This email has already been taken.";
      } else {
        $request->user()->email = $request->email;
        $request->user()->email_verified_at = NULL;
        $request->user()->status = 'Unverified';
        $request->user()->save();
        $request->user()->sendEmailVerificationNotification();
        return redirect('email/verify');
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
    $request->user()->payment_type = $request->payment_type;

    if ($request->payment_type == "btc") {
      if ($request->btc_address == null) {
        $request->user()->save();
        return back()->with("status", ["warning", "Missing BTC address."]);
      } else {
        $btc_check = User::where('btc_address', $request->btc_address)->where('btc_address', '!=', '')->value('id');
        if ($btc_check) {
          $status = "This BTC address already in use.";
        } else {
          $request->user()->btc_address = $request->btc_address;
        }
      }
    }

    if ($request->payment_type == "coinbase") {
      if ($request->coinbase_email == null) {
        $request->user()->save();
        $status = "Missing Coinbase email address.";
      } else {
        $coinbase_check = User::where('coinbase_email', $request->coinbase_email)->where('coinbase_email', '!=', '')->value('id');
        if ($coinbase_check) {
          $status = "This Coinbase email address already in use.";
        } else {
          $request->user()->coinbase_email = $request->coinbase_email;
        }
      }
    }

    if (
      $request->user()->isDirty('name') ||
      $request->user()->isDirty('surname') ||
      $request->user()->isDirty('country') ||
      $request->user()->isDirty('username') ||
      $request->user()->isDirty('referral_notification') ||
      $request->user()->isDirty('commission_notification') ||
      $request->user()->isDirty('pm_notification') ||
      $request->user()->isDirty('payment_type') ||
      $request->user()->isDirty('btc_address') ||
      $request->user()->isDirty('coinbase_email')
    ) {
      $request->user()->save();
      return back()->with("status", ["success", "Profile updated."]);
    } else {
      return $status ? back()->with("status", ["warning", $status]) : back();
    }
  }

  public function change_email(Request $request)
  {
    $request->validate([
      "email" => "required|email"
    ]);

    $email_check = User::where('email', $request->email)->value('id');
    if ($email_check) {
      return back()->with("status", ["warning", "This email has already been taken."]);
    } else {
      $request->user()->email = $request->email;
      $request->user()->email_verified_at = NULL;
      $request->user()->status = 'Unverified';
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
      $request->user()->password = $request->new_password; // not hashed because User model hashes password before saving
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

  public function suspended()
  {
    $page = "Suspended";
    return view('suspended', compact('page'));
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

  public function commissions(Request $request)
  {
    $page = "Commissions";
    $commissions_unpaid = $request->user()->commissions_unpaid;
    $commissions_paid = $request->user()->commissions_paid;
    $commissions_all = $request->user()->commissions_all;
    $commissions_transferred = $request->user()->commissions_transferred;
    return view('user/commissions', compact('page', 'commissions_unpaid', 'commissions_paid', 'commissions_all', 'commissions_transferred'));
  }

  public function transfer_credits(Request $request, $id)
  {
    $page = "Transfer Credits";
    $transfer_to = User::where('id', $id)->value('username');
    return view('user/transfer_credits', compact('page', 'transfer_to'));
  }

  public function transfer_credits_post(Request $request, $id)
  {
    $request->validate([
      'credits' => 'required|min:1|max:' . $request->user()->credits
    ]);
    $upline_check = User::where('id', $id)->value('upline');
    if (Auth::id() != $upline_check) {
      return back()->with('status', ['warning', 'You can transfer credits only to your referrals.']);
    }

    $request->user()->decrement('credits', $request->credits);
    User::where('id', $id)->increment('credits', $request->credits);

    return back()->with('status', ['success', "$request->credits credits successfully transferred to " . User::where('id', $id)->value('username')]);
  }

  public function ref_link(Request $request, $username)
  {
    Cookie::queue("hits_exchange_ref", $username, 60 * 24 * 30);
    $tracker = $request->query("t") ? $request->query("t") : null;
    PromoTracker::updateOrInsert([
      "user_id" => User::where("username", $username)->value("id"),
      "tracker_name" => $tracker,
    ])->increment("total_hits");
    Cookie::queue("hits_exchange_tracker", $tracker, 60 * 24 * 30);
    return redirect('/');
  }

  public function promote()
  {
    $page = "Affiliate Links";
    $splash_pages = SplashPage::all();
    return view('user/promote', compact('page', 'splash_pages'));
  }

  public function upgrade()
  {
    $page = "Upgrade";
    $user_types = UserType::all();
    return view('user/upgrade', compact('page', 'user_types'));
  }

  public function terms()
  {
    $page = "Terms of Service";
    return view('terms', compact('page'));
  }

  public function privacy()
  {
    $page = "Privacy Policy";
    return view('privacy', compact('page'));
  }

  public function stats()
  {
    $page = "Account Stats";
    return view('user.stats', compact('page'));
  }
}
