<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\StartPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    $request->validate([
      "start_page_user_website" => "required_if:new_start_page_url,null",
      "new_start_page_url" => "required_if:start_page_user_website,0",
      "selected_dates" => "required"
    ]);

    if ($request->start_page_user_website != 0 && $request->new_start_page_url != "") {
      return back()->with("status", ["warning", "Either specify a new URL or select one from your websites. Not both."])->withInput();
    }

    $selected_dates_length = explode(",", $request->selected_dates);
    $selected_dates = implode(", ", $selected_dates_length);

    Order::create([
      "user_id" => Auth::id(),
      "order_type" => "Start Page",
      "order_item" => "Start Page (" . $selected_dates . ")",
      "price" => count($selected_dates_length) * 1, // TODO: get start page price from database
      "status" => "Waiting Payment"
    ]);

    return redirect("user/orders");
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
