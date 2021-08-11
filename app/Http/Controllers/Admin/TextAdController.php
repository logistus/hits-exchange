<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\TextAd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\BannedUrlController;

class TextAdController extends Controller
{
  public function list_text_ads(Request $request)
  {
    if (!$request->cookie('text_ads_list_per_page') && $request->query('per_page') == "") {
      Cookie::queue('text_ads_list_per_page', 25);
    }
    if ((!$request->cookie('text_ads_list_per_page') && $request->query('per_page') != "") || ($request->cookie('text_ads_list_per_page') && $request->query('per_page') != "")) {
      Cookie::queue('text_ads_list_per_page', $request->query('per_page'));
    }
    $per_page =  $request->query('per_page') ? $request->query('per_page') : $request->cookie('text_ads_list_per_page');

    $sort = $request->query('sort') ? $request->query('sort') : "desc";
    $sort_by = $request->query('sort_by') ? $request->query('sort_by') : 'id';

    $filterStatus = $request->query('filterByStatus');
    $filterUsername = $request->query('filterByUsername');
    $filterBody = $request->query('filterByBody');
    $filterTargetUrl = $request->query('filterByTargetUrl');

    $text_ads = TextAd::query();

    $text_ads = TextAd::when($filterStatus, function ($query, $filterStatus) {
      return $query->where('status', $filterStatus);
    })->when($filterUsername, function ($query, $filterUsername) {
      return $query->where('user_id', User::where('username', $filterUsername)->value('id'));
    })->when($filterBody, function ($query, $filterBody) {
      return $query->where('body', 'LIKE', '%' . $filterBody . '%');
    })->when($filterTargetUrl, function ($query, $filterTargetUrl) {
      return $query->where('target_url', 'LIKE', '%' . $filterTargetUrl . '%');
    })->orderBy($sort_by, $sort)->paginate($per_page)->withQueryString();


    return view('admin.text_ads.list', compact('text_ads', 'per_page'));
  }

  public function pause_text_ad($id)
  {
    $text_ad = TextAd::findOrFail($id);
    $text_ad->status = 'Paused';
    $text_ad->save();
    return back();
  }

  public function activate_text_ad($id)
  {
    $text_ad = TextAd::findOrFail($id);
    $text_ad->status = 'Active';
    $text_ad->save();
    return back();
  }

  public function suspend_text_ad($id)
  {
    $text_ad = TextAd::findOrFail($id);
    $text_ad->status = 'Suspended';
    $text_ad->save();
    return back();
  }

  public function destroy($id)
  {
    $text_ad = TextAd::findOrFail($id);
    $text_ad->delete();
    return back();
  }

  public function actions_text_ads(Request $request)
  {
    switch ($request->action) {
      case "delete_selected":
        $square_banners = $request->selected_banners;
        foreach ($square_banners as $id) {
          $this->destroy($id);
        }
        return back();
        break;
      case "suspend_selected":
        $square_banners = $request->selected_banners;
        foreach ($square_banners as $id) {
          $this->suspend_text_ad($id);
        }
        return back();
        break;
      case "activate_selected":
        $square_banners = $request->selected_banners;
        foreach ($square_banners as $id) {
          $this->activate_text_ad($id);
        }
        return back();
        break;
    }
  }

  public function add_text_ad_get()
  {
    return view('admin.text_ads.add');
  }

  public function add_text_ad_post(Request $request)
  {
    $request->validate([
      'target_url' => 'required|url',
      'body' => 'required',
      'username' => 'required|exists:users,username',
      'assigned' => 'required|numeric|min:0',
    ]);

    $isBannedTargetUrl = BannedUrlController::check_banned($request->target_url);

    if ($isBannedTargetUrl) {
      return back()->with('status', ['warning', $isBannedTargetUrl])->withInput();
    } else {
      $text_ad = TextAd::create($request->only('assigned', 'text_color', 'bg_color', 'text_bold', 'body', 'status'));

      $text_ad->target_url = str_replace("http://", "https://", $request->target_url);
      $text_ad->user_id = User::where('username', $request->username)->value('id');
      $text_ad->save();
    }
    return redirect('admin/text_ads/list');
  }

  public function edit_text_ad_get($id)
  {
    $text_ad = TextAd::findOrFail($id);
    return view('admin.text_ads.edit', compact('text_ad'));
  }

  public function edit_text_ad_post(Request $request, $id)
  {
    $request->validate([
      'body' => 'required',
      'target_url' => 'required|url',
      'username' => 'required|exists:users,username',
      'assigned' => 'required|numeric|min:0',
    ]);

    $text_ad = TextAd::findOrFail($id);

    $isBannedTargetUrl = BannedUrlController::check_banned($request->target_url);

    if ($isBannedTargetUrl) {
      return back()->with('status', ['warning', $isBannedTargetUrl])->withInput();
    } else {
      $text_ad->target_url = str_replace("http://", "https://", $request->target_url);
      $text_ad->user_id = User::where('username', $request->username)->value('id');
      $text_ad->assigned = $request->assigned;
      $text_ad->bg_color = $request->bg_color;
      $text_ad->text_color = $request->text_color;
      $text_ad->text_bold = $request->text_bold;
      $text_ad->status = $request->status;
      $text_ad->save();
    }

    return redirect('admin/text_ads/list')->with('status', ['success', 'Text Ad updated.']);
  }
}
