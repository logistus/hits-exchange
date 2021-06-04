<?php

namespace App\Http\Controllers;

use App\Models\StartPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StartPageController extends Controller
{

  public function index(Request $request)
  {
    $start_pages = $request->user()->start_pages;
    $page = "Start Pages";
    return view('user.start_pages', compact('page', 'start_pages'));
  }

  public function index_buy(Request $request)
  {
    $page = "Buy Startpage";
    $user_websites = $request->user()->websites;
    $bought_dates = StartPage::select('start_date')->where('status', 'Active')->get();
    $user_start_pages = $request->user()->start_pages;
    return view('buy.start_page', compact('page', 'bought_dates', 'user_start_pages', 'user_websites'));
  }

  public function store_buy(Request $request)
  {
    return $request->all();
  }

  public function destroy($id)
  {
    $start_page = StartPage::findOrFail($id);
    $response = Gate::inspect("delete", $start_page);
    if ($response->allowed()) {
      $start_page->delete();
      return back();
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }
}
