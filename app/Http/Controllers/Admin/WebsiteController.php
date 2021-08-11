<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\BannedUrlController;

class WebsiteController extends Controller
{

  public function list_websites(Request $request)
  {
    if (!$request->cookie('websites_list_per_page') && $request->query('per_page') == "") {
      Cookie::queue('websites_list_per_page', 25);
    }
    if ((!$request->cookie('websites_list_per_page') && $request->query('per_page') != "") || ($request->cookie('websites_list_per_page') && $request->query('per_page') != "")) {
      Cookie::queue('websites_list_per_page', $request->query('per_page'));
    }
    $per_page =  $request->query('per_page') ? $request->query('per_page') : $request->cookie('websites_list_per_page');

    $sort = $request->query('sort') ? $request->query('sort') : "desc";
    $sort_by = $request->query('sort_by') ? $request->query('sort_by') : 'id';

    $filterStatus = $request->query('filterByStatus');
    $filterUsername = $request->query('filterByUsername');
    $filterUrl = $request->query('filterByUrl');

    $websites = Website::query();

    $websites = Website::when($filterStatus, function ($query, $filterStatus) {
      return $query->where('status', $filterStatus);
    })->when($filterUsername, function ($query, $filterUsername) {
      return $query->where('user_id', User::where('username', $filterUsername)->value('id'));
    })->when($filterUrl, function ($query, $filterUrl) {
      return $query->where('url', 'LIKE', '%' . $filterUrl . '%');
    })->orderBy($sort_by, $sort)->paginate($per_page)->withQueryString();


    return view('admin.websites.list', compact('websites', 'per_page'));
  }

  public function pause_website($id)
  {
    $website = Website::findOrFail($id);
    $website->status = 'Paused';
    $website->save();
    return back();
  }

  public function activate_website($id)
  {
    $website = Website::findOrFail($id);
    $website->status = 'Active';
    $website->save();
    return back();
  }

  public function suspend_website($id)
  {
    $website = Website::findOrFail($id);
    $website->status = 'Suspended';
    $website->save();
    return back();
  }

  public function destroy($id)
  {
    $website = Website::findOrFail($id);
    $website->delete();
    return back();
  }

  public function actions_websites(Request $request)
  {
    switch ($request->action) {
      case "delete_selected":
        $websites = $request->selected_websites;
        foreach ($websites as $id) {
          $this->destroy($id);
        }
        return back();
        break;
      case "suspend_selected":
        $websites = $request->selected_websites;
        foreach ($websites as $id) {
          $this->suspend_website($id);
        }
        return back();
        break;
      case "activate_selected":
        $websites = $request->selected_websites;
        foreach ($websites as $id) {
          $this->activate_website($id);
        }
        return back();
        break;
    }
  }

  public function add_website_get()
  {
    return view('admin.websites.add');
  }

  public function add_website_post(Request $request)
  {
    $request->validate([
      'url' => 'required|url',
      'username' => 'required|exists:users,username',
      'assigned' => 'required|numeric|min:0',
      'max_daily_views' => 'required|numeric|min:0',
      'auto_assign' => 'required|numeric|min:0'
    ]);

    $isBanned = BannedUrlController::check_banned($request->url);

    if ($isBanned) {
      return back()->with('status', ['warning', $isBanned])->withInput();
    } else {
      $website = Website::create($request->only('assigned', 'max_daily_views', 'auto_assign', 'status'));

      $website->url = str_replace("http://", "https://", $request->url);
      $website->user_id = User::where('username', $request->username)->value('id');
      $website->save();
    }
    return redirect('admin/websites/list');
  }

  public function edit_website_get($id)
  {
    $website = Website::findOrFail($id);
    return view('admin.websites.edit', compact('website'));
  }

  public function edit_website_post(Request $request, $id)
  {
    $request->validate([
      'url' => 'required|url',
      'username' => 'required|exists:users,username',
      'assigned' => 'required|numeric|min:0',
      'max_daily_views' => 'required|numeric|min:0',
      'auto_assign' => 'required|numeric|min:0'
    ]);

    $website = Website::findOrFail($id);

    $isBanned = BannedUrlController::check_banned($request->url);

    if ($isBanned) {
      return back()->with('status', ['warning', $isBanned])->withInput();
    } else {
      $website->url = str_replace("http://", "https://", $request->url);
      $website->user_id = User::where('username', $request->username)->value('id');
      $website->assigned = $request->assigned;
      $website->max_daily_views = $request->max_daily_views;
      $website->auto_assign = $request->auto_assign;
      $website->status = $request->status;
      $website->save();
    }

    return redirect('admin/websites/list')->with('status', ['success', 'Website updated.']);
  }
}
