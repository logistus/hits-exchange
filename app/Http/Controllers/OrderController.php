<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PurchaseBalance;
use App\Models\StartPage;
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

  public function pay_with_purchase_balance(Request $request, $id)
  {
    $order = Order::findOrFail($id);
    $response = Gate::inspect("update", $order);
    if ($response->allowed()) {
      PurchaseBalance::insert([
        'user_id' => $request->user()->id,
        'order_id' => $id,
        'type' => 'Purchase',
        'amount' => '-' . $order->price
      ]);
      $order->update(['status' => 'Completed']);
      if ($order->order_type == "Start Page") {
        StartPage::where('order_id', $order->id)->update(['status' => 'Active']);
      }
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
    return back();
  }
}
