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

    Order::create([
      'user_id' => Auth::user()->id,
      'order_item' => $order_item,
      'price' => $price->price,
      'status' => 'Pending Payment'
    ]);
    return redirect('user/orders');
  }
}
