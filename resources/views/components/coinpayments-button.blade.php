<form action="https://www.coinpayments.net/index.php" method="post" class="text-center mt-3" target="_top">
  <input type="hidden" name="cmd" value="_pay">
  <input type="hidden" name="reset" value="1">
  <input type="hidden" name="merchant" value="0a163329f1a618ee280c49eb1db2d9c2">
  <input type="hidden" name="currency" value="USD">
  <input type="hidden" name="amountf" value={{ $amount }}>
  <input type="hidden" name="want_shipping" value="0">
  <input type="hidden" name="allow_extra" value="0">
  <input type="hidden" name="first_name" value="{{ Auth::user()->name }}">
  <input type="hidden" name="last_name" value="{{ Auth::user()->surname }}">
  <input type="hidden" name="email" value="{{ Auth::user()->email }}">
  <input type="hidden" name="invoice" value={{ $id }}>
  <input type="hidden" name="item_name" value="{{ $item }}">
  <input type="hidden" name="cancel_url" value="{{ $cancelUrl }}">
  <input type="hidden" name="success_url" value="{{ $successUrl }}">
  <input type="hidden" name="ipn_url" value="{{ url('buy/ipn') }}">
  <input type="image" src="https://www.coinpayments.net/images/pub/buynow-wide-blue.png" alt="Buy Now with CoinPayments.net">
</form>
