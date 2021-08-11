<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\BannedUrlController;

class BannerController extends Controller
{
  public function list_banners(Request $request)
  {
    if (!$request->cookie('banners_list_per_page') && $request->query('per_page') == "") {
      Cookie::queue('banners_list_per_page', 25);
    }
    if ((!$request->cookie('banners_list_per_page') && $request->query('per_page') != "") || ($request->cookie('banners_list_per_page') && $request->query('per_page') != "")) {
      Cookie::queue('banners_list_per_page', $request->query('per_page'));
    }
    $per_page =  $request->query('per_page') ? $request->query('per_page') : $request->cookie('banners_list_per_page');

    $sort = $request->query('sort') ? $request->query('sort') : "desc";
    $sort_by = $request->query('sort_by') ? $request->query('sort_by') : 'id';

    $filterStatus = $request->query('filterByStatus');
    $filterUsername = $request->query('filterByUsername');
    $filterImageUrl = $request->query('filterByImageUrl');
    $filterTargetUrl = $request->query('filterByTargetUrl');

    $banners = Banner::query();

    $banners = Banner::when($filterStatus, function ($query, $filterStatus) {
      return $query->where('status', $filterStatus);
    })->when($filterUsername, function ($query, $filterUsername) {
      return $query->where('user_id', User::where('username', $filterUsername)->value('id'));
    })->when($filterImageUrl, function ($query, $filterImageUrl) {
      return $query->where('image_url', 'LIKE', '%' . $filterImageUrl . '%');
    })->when($filterTargetUrl, function ($query, $filterTargetUrl) {
      return $query->where('target_url', 'LIKE', '%' . $filterTargetUrl . '%');
    })->orderBy($sort_by, $sort)->paginate($per_page)->withQueryString();


    return view('admin.banners.list', compact('banners', 'per_page'));
  }

  public function pause_banner($id)
  {
    $banner = Banner::findOrFail($id);
    $banner->status = 'Paused';
    $banner->save();
    return back();
  }

  public function activate_banner($id)
  {
    $banner = Banner::findOrFail($id);
    $banner->status = 'Active';
    $banner->save();
    return back();
  }

  public function suspend_banner($id)
  {
    $banner = Banner::findOrFail($id);
    $banner->status = 'Suspended';
    $banner->save();
    return back();
  }

  public function destroy($id)
  {
    $banner = Banner::findOrFail($id);
    $banner->delete();
    return back();
  }

  public function actions_banners(Request $request)
  {
    switch ($request->action) {
      case "delete_selected":
        $banners = $request->selected_banners;
        foreach ($banners as $id) {
          $this->destroy($id);
        }
        return back();
        break;
      case "suspend_selected":
        $banners = $request->selected_banners;
        foreach ($banners as $id) {
          $this->suspend_banner($id);
        }
        return back();
        break;
      case "activate_selected":
        $banners = $request->selected_banners;
        foreach ($banners as $id) {
          $this->activate_banner($id);
        }
        return back();
        break;
    }
  }

  public function add_banner_get()
  {
    return view('admin.banners.add');
  }

  public function add_banner_post(Request $request)
  {
    $request->validate([
      'target_url' => 'required|url',
      'image_url' => 'required|url',
      'username' => 'required|exists:users,username',
      'assigned' => 'required|numeric|min:0',
    ]);

    $isBannedImageUrl = BannedUrlController::check_banned($request->image_url);
    $isBannedTargetUrl = BannedUrlController::check_banned($request->target_url);

    if ($isBannedImageUrl || $isBannedTargetUrl) {
      return back()->with('status', ['warning', $isBannedTargetUrl ? $isBannedTargetUrl : $isBannedImageUrl])->withInput();
    } else {
      $banner = Banner::create($request->only('assigned', 'status'));

      $banner->target_url = str_replace("http://", "https://", $request->target_url);
      $banner->image_url = str_replace("http://", "https://", $request->image_url);
      $banner->user_id = User::where('username', $request->username)->value('id');
      $banner->save();
    }
    return redirect('admin/banners/list');
  }

  public function edit_banner_get($id)
  {
    $banner = Banner::findOrFail($id);
    return view('admin.banners.edit', compact('banner'));
  }

  public function edit_banner_post(Request $request, $id)
  {
    $request->validate([
      'target_url' => 'required|url',
      'image_url' => 'required|url',
      'username' => 'required|exists:users,username',
      'assigned' => 'required|numeric|min:0',
    ]);

    $banner = Banner::findOrFail($id);

    $isBannedImageUrl = BannedUrlController::check_banned($request->image_url);
    $isBannedTargetUrl = BannedUrlController::check_banned($request->target_url);

    if ($isBannedImageUrl || $isBannedTargetUrl) {
      return back()->with('status', ['warning', $isBannedTargetUrl ? $isBannedTargetUrl : $isBannedImageUrl])->withInput();
    } else {
      $banner->image_url = str_replace("http://", "https://", $request->image_url);
      $banner->target_url = str_replace("http://", "https://", $request->target_url);
      $banner->user_id = User::where('username', $request->username)->value('id');
      $banner->assigned = $request->assigned;
      $banner->status = $request->status;
      $banner->save();
    }

    return redirect('admin/banners/list')->with('status', ['success', 'Banner updated.']);
  }
}
