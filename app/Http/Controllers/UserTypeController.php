<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UpgradePrice;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTypeController extends Controller
{
  public function store_buy(Request $request, $type_id, $price_id)
  {
    $price = UpgradePrice::findOrFail($price_id);
    $type = UserType::findOrFail($type_id);
    $order_item = $price->time_amount . " " . $price->time_type;
    if ($price->time_amount > 1)
      $order_item .= "s";
    $order_item .= " " . $type->name . " Member";
    // Calculate order_amount as days
    if ($price->time_type == "Day") {
      $order_amount = $price->time_amount;
    } else if ($price->time_type == "Week") {
      $order_amount = $price->time_amount * 7;
    } else if ($price->time_type == "Month") {
      $order_amount = $price->time_amount * 30;
    } else {
      $order_amount = $price->time_amount * 365;
    }

    Order::create([
      'user_id' => Auth::user()->id,
      'order_type' => 'Upgrade',
      'order_item' => $order_item,
      'order_amount' => $order_amount,
      'order_member_type' => $type->id,
      'price' => $price->price,
      'status' => 'Pending Payment'
    ]);
    return redirect('user/orders');
  }
}
