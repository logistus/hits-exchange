<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
  public function index()
  {
    $page = "Orders";
    $orders = Auth::user()->orders;
    return view('user/orders', compact('page', 'orders'));
  }
}
