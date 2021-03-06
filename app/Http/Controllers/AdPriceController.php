<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\AdPrice;
use Illuminate\Http\Request;

class AdPriceController extends Controller
{
  public function index()
  {
    $page = "Buy Credits & Impressions";
    $credit_ad_prices = AdPrice::where('ad_type', 'Credits')->orderBy('ad_amount')->get();
    $banner_ad_prices = AdPrice::where('ad_type', 'Banner Impressions')->orderBy('ad_amount')->get();
    $square_banner_ad_prices = AdPrice::where('ad_type', 'Square Banner Impressions')->orderBy('ad_amount')->get();
    $text_ad_prices = AdPrice::where('ad_type', 'Text Impressions')->orderBy('ad_amount')->get();

    return view('buy/credits', compact('page', 'credit_ad_prices', 'banner_ad_prices', 'square_banner_ad_prices', 'text_ad_prices'));
  }

  public function store(Request $request, $id)
  {
    $ad_unit = AdPrice::findOrFail($id);

    Order::create([
      'user_id' => $request->user()->id,
      'order_type' => $ad_unit->ad_type,
      'order_item' => $ad_unit->ad_amount . ' ' . $ad_unit->ad_type,
      'order_amount' => $ad_unit->ad_amount,
      'price' => $ad_unit->price
    ]);

    return redirect('user/orders');
  }
}
