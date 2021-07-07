<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/purchase_balance/deposit') }}">Deposit Purchase Balance</a></h4>
  <div class="row">
    <div class="col-3">
      <div class="card" style="width: 18rem;">
        <img src="{{ asset('images/coinpayments.svg') }}" class="card-img-top p-3" alt="CoinPayments" title="CoinPayments" />
        <div class="card-body">
          <p class="card-text">Enter the desired amount below and click "Pay Using CoinPayments" button.</p>
          <input type="number" name="deposit_amount_coinpayments" id="deposit_amount_coinpayments" placeholder="Deposit Amount in USD" class="form-control">
          <form action="https://www.coinpayments.net/index.php" method="post" class="text-center mt-3" target="_top">
            <input type="hidden" name="cmd" value="_pay_simple">
            <input type="hidden" name="reset" value="1">
            <input type="hidden" name="want_shipping" value="0">
            <input type="hidden" name="merchant" value="0a163329f1a618ee280c49eb1db2d9c2">
            <input type="hidden" name="currency" value="USD">
            <input type="hidden" name="amountf">
            <input type="hidden" name="allow_extra" value="0">
            <input type="hidden" name="item_name" value="Purchase Balance Deposit">
            <input type="hidden" name="cancel_url" value="{{ url('user/purchase_balance/deposit') }}">
            <input type="image" src="https://www.coinpayments.net/images/pub/buynow-wide-blue.png" alt="Buy Now with CoinPayments.net">
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script>
    $(function() {
      $("#deposit_amount_coinpayments").keyup(function() {
        $("[name='amountf']").val($(this).val());
      });
    });

  </script>
</x-layout>
