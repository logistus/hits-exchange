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
    // Check if user has sufficent completed purchase balance in account
    if ($request->user()->purchase_balance_completed->sum('amount') < $order->price) {
      return back()->with("status", ["warning", "You don't have enough purchase balance to pay for this order."]);
    }
    if ($response->allowed()) {
      PurchaseBalance::insert([
        'user_id' => $request->user()->id,
        'order_id' => $id,
        'type' => 'Purchase',
        'amount' => '-' . $order->price,
        'status' => 'Completed'
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

  public function ipn()
  {
    $cp_merchant_id = '0a163329f1a618ee280c49eb1db2d9c2';
    $cp_ipn_secret = 'etfjd8dP2JuQBTEL9gk4';

    function errorAndDie($error_msg)
    {
      global $cp_debug_email;
      if (!empty($cp_debug_email)) {
        $report = 'Error: ' . $error_msg . "\n\n";
        $report .= "POST Data\n\n";
        foreach ($_POST as $k => $v) {
          $report .= "|$k| = |$v|\n";
        }
        mail($cp_debug_email, 'CoinPayments IPN Error', $report);
      }
      die('IPN Error: ' . $error_msg);
    }

    if (!isset($_POST['ipn_mode']) || $_POST['ipn_mode'] != 'hmac') {
      errorAndDie('IPN Mode is not HMAC');
    }

    if (!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
      errorAndDie('No HMAC signature sent.');
    }

    $request = file_get_contents('php://input');
    if ($request === FALSE || empty($request)) {
      errorAndDie('Error reading POST data');
    }

    if (!isset($_POST['merchant']) || $_POST['merchant'] != trim($cp_merchant_id)) {
      errorAndDie('No or incorrect Merchant ID passed');
    }

    $hmac = hash_hmac("sha512", $request, trim($cp_ipn_secret));
    if (!hash_equals($hmac, $_SERVER['HTTP_HMAC'])) {
      //if ($hmac != $_SERVER['HTTP_HMAC']) { <-- Use this if you are running a version of PHP below 5.6.0 without the hash_equals function
      errorAndDie('HMAC signature does not match');
    }

    // HMAC Signature verified at this point, load some variables.

    $ipn_type = $_POST['ipn_type'];
    $txn_id = $_POST['txn_id'];
    $item_name = $_POST['item_name'];
    $item_number = $_POST['item_number'];
    $amount1 = floatval($_POST['amount1']);
    $amount2 = floatval($_POST['amount2']);
    $currency1 = $_POST['currency1'];
    $currency2 = $_POST['currency2'];
    $status = intval($_POST['status']);
    $status_text = $_POST['status_text'];

    $pb = PurchaseBalance::findOrFail('id', $_POST['custom']);

    //These would normally be loaded from your database, the most common way is to pass the Order ID through the 'custom' POST field.
    $order_currency = 'USD';
    $order_total = $pb->amount;

    if ($ipn_type != 'button') { // Advanced Button payment
      die("IPN OK: Not a button payment");
    }

    //depending on the API of your system, you may want to check and see if the transaction ID $txn_id has already been handled before at this point

    // Check the original currency to make sure the buyer didn't change it.
    if ($currency1 != $order_currency) {
      errorAndDie('Original currency mismatch!');
    }

    // Check amount against order total
    if ($amount1 < $order_total) {
      errorAndDie('Amount is less than order total!');
    }

    $pb->txn_id = $txn_id;

    if ($status >= 100 || $status == 2) {
      $pb->status = 'Completed';
    } else if ($status < 0) {
      return back()->with('status', ['warning', 'An error accured, try again later please.']);
    } else {
      //$pb->txn_id = $txn_id;
    }
    $pb->save();
    die('IPN OK');
  }
}
