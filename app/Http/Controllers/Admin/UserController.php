<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Country;
use App\Models\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class UserController extends Controller
{

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
    $filterUpline = $request->query("filterByUpline");
    $filterStatus = $request->query("filterByStatus");
    $filterNoUpline = $request->query("filterByNoUpline");

    $users = User::query();

    $users = User::when($filterUsername, function ($query, $filterUsername) {
      return $query->where('username', $filterUsername);
    })->when($filterEmail, function ($query, $filterEmail) {
      return $query->where('email', $filterEmail);
    })->when($filterUserType, function ($query, $filterUserType) {
      return $query->where('user_type', $filterUserType);
    })->when($filterUpline, function ($query, $filterUpline) {
      return $query->where('upline', '=', User::where('username', $filterUpline)->value('id'))->where('upline', '<>', NULL);
    })->when($filterNoUpline, function ($query) {
      return $query->where('upline', NULL);
    })->when($filterStatus, function ($query, $filterStatus) {
      return $query->where('status', $filterStatus);
    })->orderBy($sort_by, $sort)->paginate($per_page)->withQueryString();


    return view('admin.members.list', compact('users', 'per_page', 'user_types'));
  }

  public function add_user_get()
  {
    $countries = Country::orderBy('name', 'asc')->get();
    $user_types = UserType::all();
    return view('admin.members.add', compact('countries', 'user_types'));
  }

  public function add_user_post(Request $request)
  {
    $request->validate([
      "name" => "required|string",
      "surname" => "required|string",
      "username" => "required|unique:users",
      "email" => "required|email|unique:users",
      "country" => "required",
      "password" => "required|min:8",
      "upline" => "exists:users,username|nullable",
      "join_date" => "required|date",
    ]);

    $user = User::create($request->only('join_date', 'name', 'surname', 'username', 'email', 'country', 'password', 'status', 'user_type'));

    if ($request->status === "Active") {
      $user->email_verified_at = now();
    }

    if ($request->upline) {
      $user->upline = User::where('username', $request->upline)->value('id');
    }

    if ($request->suspend_reason) {
      $user->suspend_reason = $request->suspend_reason;
    }

    if ($request->suspend_until) {
      $user->suspend_until = $request->suspend_until;
    }

    $user->referral_notification = $request->input('referral_notification') ? 1 : 0;
    $user->commission_notification = $request->input('commission_notification') ? 1 : 0;
    $user->pm_notification = $request->input('pm_notification') ? 1 : 0;

    $user->save();

    return redirect('admin/members/list');
  }

  public function actions_members(Request $request)
  {
    $user = User::find($request->user_id);
    switch ($request->action) {
      case "delete":
        $user->delete();
        return back();
      case "unsuspend":
        $user->status = "Active";
        $user->suspend_reason = "";
        $user->suspend_until = NULL;
        $user->save();
        return back();
      case "verify":
        $user->email_verified_at = now();
        $user->status = "Active";
        $user->save();
        return back();
    }
  }

  public function suspend(Request $request)
  {
    $user = User::find($request->user_id);
    $suspend_reason = $request->suspend_reason;;
    $suspend_until = $request->suspend_until;
    $user->status = "Suspended";
    if ($suspend_reason)
      $user->suspend_reason = $suspend_reason;
    if ($suspend_until)
      $user->suspend_until = $suspend_until;
    $user->save();
    return redirect('admin/members/list');
  }

  public function edit_user_get($id)
  {
    $user = User::findOrFail($id);
    $countries = Country::orderBy('name', 'asc')->get();
    $user_types = UserType::all();
    return view('admin/members/edit', compact('user', 'countries', 'user_types'));
  }

  public function edit_user_post(Request $request, $id)
  {
    $request->validate([
      "name" => "required|string",
      "surname" => "required|string",
      "username" => "required",
      "email" => "required|email",
      "country" => "required",
      "join_date" => "required|date",
    ]);

    $user = User::findOrFail($id);

    $status = null;

    if ($user->email != $request->email) {
      $email_check = User::where('email', $request->email)->value('id');
      if ($email_check) {
        $status = "$request->email has already been taken.";
      } else {
        $user->email = $request->email;
        $user->email_verified_at = date("Y-m-d");
      }
    }

    if ($user->username != $request->username) {
      $username_check = User::where('username', $request->username)->value('id');
      if ($username_check) {
        $status = "$request->username has already been taken.";
      } else {
        $user->username = $request->username;
      }
    }

    if ($request->password != "") {
      $user->password = $request->password;
    }

    if ($request->upline) {
      $upline_id =  User::where('username', $request->upline)->value('id');
      if ($upline_id) {
        $user->upline = $upline_id;
      } else {
        $status = "Member $request->upline could not found (wanted to set as upline).";
      }
    }

    $user->name = $request->name;
    $user->surname = $request->surname;
    $user->country = $request->country;
    $user->referral_notification = $request->input('referral_notification') ? 1 : 0;
    $user->commission_notification = $request->input('commission_notification') ? 1 : 0;
    $user->pm_notification = $request->input('pm_notification') ? 1 : 0;
    $user->join_date = $request->join_date;
    $user->status = $request->status;
    $user->suspend_reason = $request->suspend_reason;
    $user->suspend_until = $request->suspend_until;
    $user->user_type = $request->user_type;

    $user->save();

    return $status ? back()->with("status", ["warning", $status]) : redirect('admin/members/list')->with("status", ["success", "Member $user->username updated."]);
  }
}
