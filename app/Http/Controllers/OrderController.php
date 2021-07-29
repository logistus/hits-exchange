<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\StartPage;
use App\Models\Commission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LoginSpotlight;
use App\Models\PurchaseBalance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Notifications\CommissionEarned;
use Carbon\Carbon;

class OrderController extends Controller
{
  public function index()
  {
    $page = "Orders";
    $orders = Auth::user()->orders;
    return view('user/orders', compact('page', 'orders'));
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
      // Start Page Order
      if ($order->order_type == "Start Page") {
        StartPage::where('order_id', $order->id)->update(['status' => 'Active']);
      }
      // Login Spotlight Order
      if ($order->order_type == "Login Spotlight") {
        LoginSpotlight::where('order_id', $order->id)->update(['status' => 'Active']);
      }
      // Upgrade
      if ($order->order_type == "Upgrade") {
        if ($request->user()->upgrade_expires != NULL) {
          $expires = Carbon::createFromFormat("Y-m-d h:i:s", $request->user()->upgrade_expires)->add($order->order_amount, 'day')->timestamp;
        } else {
          $expires = Carbon::now()->add($order->order_amount, 'day')->timestamp;
        }
        //dd($expires, now()->timestamp);
        User::where('id', Auth::id())->update([
          'user_type' => $order->order_member_type,
          'upgrade_expires' => $expires,
        ]);
      }
      // Credits
      if ($order->order_type == "Credits") {
        User::where('id', Auth::id())->increment("credits", $order->order_amount);
      }
      // Banner Impressions
      if ($order->order_type == "Banner Impressions") {
        User::where('id', Auth::id())->increment("banner_imps", $order->order_amount);
      }
      // Square Banner Impressions
      if ($order->order_type == "Square Banner Impressions") {
        User::where('id', Auth::id())->increment("square_banner_imps", $order->order_amount);
      }
      // Text Impressions
      if ($order->order_type == "Text Impressions") {
        User::where('id', Auth::id())->increment("text_imps", $order->order_amount);
      }
      // Update user's total purchased column
      User::where('id', Auth::id())->increment('total_purchased', $order->price);
      // if user has upline add commissions
      if (Auth::user()->upline) {
        $commission_amount = ($order->price * User::where('id', Auth::user()->upline)->get()->first()->type->commission_ratio) / 100;
        $commission = Commission::create([
          'user_id' => Auth::user()->upline,
          'order_id' => $id,
          'amount' => $commission_amount
        ]);
        // Send an email to upline if he/she wants
        if (User::where('id', Auth::user()->upline)->get()->first()->commission_notification) {
          User::where('id', Auth::user()->upline)->get()->first()->notify(
            new CommissionEarned(User::where('id', Auth::user()->upline)->get()->first(), $request->user(), $order, $commission),
          );
        }
      }
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
    return back()->with("status", ["success", "Payment for " . $order->order_item . " has been successfully completed."]);
  }

  public function destroy($id)
  {
    $order = Order::findOrFail($id);
    $response = Gate::inspect("delete", $order);
    if ($response->allowed()) {
      $order->delete();
      if (Str::startsWith($order->order_item, "Start Page"))
        StartPage::where('order_id', $order->id)->delete();
      if (Str::startsWith($order->order_item, "Login Spotlight"))
        LoginSpotlight::where('order_id', $order->id)->delete();
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
    return back();
  }
}
