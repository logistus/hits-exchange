<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use Illuminate\Http\Request;
use App\Models\PurchaseBalance;
use Illuminate\Support\Facades\Auth;

class PurchaseBalanceController extends Controller
{
  public function index(Request $request)
  {
    $page = "Purchase Balance";
    $purchase_balance = $request->user()->purchase_balance;
    return view('user/purchase_balance', compact('page', 'purchase_balance'));
  }

  public function deposit($id)
  {
    $page = "Deposit Purchase Balance";
    $pb = PurchaseBalance::findOrFail($id);
    return view('user/purchase_balance_deposit', compact('page', 'pb'));
  }

  public function create(Request $request)
  {
    $page = "Add Purchase Balance";
    return view('user/add_purchase_balance', compact('page'));
  }

  public function transfer_commissions()
  {
    $page = "Transfer Commissions";
    return view('user/transfer_commissions', compact('page'));
  }

  public function transfer_commissions_post(Request $request)
  {
    $request->validate([
      "commission_transfer_amount" => "required|numeric|min:0.5|max:" . $request->user()->commissions_all->sum('amount')
    ]);

    Commission::insert([
      'user_id' => $request->user()->id,
      'amount' => '-' . $request->commission_transfer_amount,
      'status' => 'Transferred'
    ]);

    PurchaseBalance::insert([
      'user_id' => $request->user()->id,
      'type' => 'Commission Transfer',
      'amount' => $request->commission_transfer_amount + ($request->commission_transfer_amount * 20) / 100,
      'status' => 'Completed'
    ]);

    return redirect('user/purchase_balance')->with('status', ['success', 'Commissions successfully transferred.']);
  }

  public function store(Request $request)
  {
    $request->validate([
      'deposit_amount' => 'required|numeric'
    ]);

    $pb = PurchaseBalance::create([
      'user_id' => Auth::id(),
      'type' => 'Deposit',
      'amount' => $request->deposit_amount
    ]);

    return redirect('user/purchase_balance/deposit/' . $pb->id);
  }
}
