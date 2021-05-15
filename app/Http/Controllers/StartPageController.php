<?php

namespace App\Http\Controllers;

use App\Models\StartPage;
use Illuminate\Http\Request;

class StartPageController extends Controller
{
  public function index(Request $request)
  {
    $page = "Buy Startpage";
    $bought_dates = StartPage::select('start_date')->where('status', 'Active')->get();
    $user_start_pages = $request->user()->start_pages;
    return view('buy.start_page', compact('page', 'bought_dates', 'user_start_pages'));
  }

  public function store(Request $request)
  {
    return $request->all();
  }
}
