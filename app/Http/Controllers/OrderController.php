<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Gate;
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

  public function destroy($id)
  {
    $order = Order::findOrFail($id);
    $response = Gate::inspect("delete", $order);
    if ($response->allowed()) {
      $order->delete();
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
    return back();
  }
}
