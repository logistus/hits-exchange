<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/purchase_balance') }}">Purchase Balance</a> > <a href="{{ url('user/purchase_balance/deposit') }}">Deposit Purchase Balance</a></h4>
  <x-alert />
  <div class="row mt-3">
    <div class="col">
      <div class="card bg-light">
        <h5 class="card-title text-center px-3 pt-3">Transfer From Commissions</h5>
        <div class="card-body pt-0">
          <p class="card-text">
            <p>You can transfer your unpaid commissions to your purchase balance.</p>
            <p><strong>20%</strong> bonus will be applied when you transfer. For example, if you transfer $1 you will get $1.20 to your purchase balance.</p>
            <p>You have ${{ number_format(Auth::user()->commissions_all->sum('amount'), 2) }} unpaid commissions.</p>
          </p>
          <form action="{{ url('user/transfer_commissions') }}" method="POST">
            @csrf
            <input type="text" name="commission_transfer_amount" id="commission_transfer_amount" placeholder="Trasnfer Amount" class="form-control">
            @error('commission_transfer_amount')
            <div class="text-danger">{{ $message }}</div>
            @enderror
            <small>Minimum amount $0.5</small>
            <button type="submit" class="btn btn-primary w-100 mt-3">Transfer</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card bg-light">
        <img src="{{ asset('images/coinbase.png') }}" class="card-img-top p-3" alt="Coinbase" title="Coinbase" />
        <div class="card-body">
          <p class="card-text">You can send bitcoin from your Coinbase account to this email address:</p>
          <p class="card-text"><strong>sinanyilmaz@yandex.com</strong></p>
          <p class="card-text">You can use <a href="https://coinmarketcap.com/converter/usd/btc/" target="_blank" rel="noopener noreferrer">this converter</a> to calculate how much BTC you need.</p>
          <p class="card-text">After you complete the transaction, fill out the form below and click "Make Coinbase Deposit" button.</p>
          <form action="{{ url('user/purchase_balance/deposit') }}" method="POST">
            @csrf
            <input type="text" name="coinbase_deposit_amount" id="coinbase_deposit_amount" placeholder="Deposit Amount in USD" class="form-control">
            <small>Minimum $2 - Maximum $500</small>
            <input type="text" name="coinbase_deposit_name" id="coinbase_deposit_name" placeholder="Name in Coinbase" class="form-control mt-3">
            <button type="submit" class="btn btn-primary w-100 mt-3">Make Coinbase Deposit</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card bg-light">
        <a href="https://www.coinpayments.net/index.php?ref=0a163329f1a618ee280c49eb1db2d9c2" target="_blank" rel="noopener noreferrer">
          <img src="{{ asset('images/coinpayments.svg') }}" class="card-img-top p-3" alt="CoinPayments" title="CoinPayments" />
        </a>
        <div class="card-body">
          <p class="card-text">Enter the desired amount below and click "Pay Using CoinPayments" button.</p>
          <p class="card-text">When you click the "Pay Using CoinPayments" button, you will be directed to the CoinPayments website.</p>
          <p class="card-text">After you complete the transaction, your deposit will be credited automatically through the CoinPayments IPN.</p>
          <input type="number" name="deposit_amount_coinpayments" id="deposit_amount_coinpayments" placeholder="Deposit Amount in USD" class="form-control">
          <form action="https://www.coinpayments.net/index.php" method="post" class="text-center mt-3" target="_top">
            <input type="hidden" name="cmd" value="_pay">
            <input type="hidden" name="reset" value="1">
            <input type="hidden" name="merchant" value="0a163329f1a618ee280c49eb1db2d9c2">
            <input type="hidden" name="currency" value="USD">
            <input type="hidden" name="amountf">
            <input type="hidden" name="want_shipping" value="0">
            <input type="hidden" name="allow_extra" value="0">
            <input type="hidden" name="first_name" value="{{ Auth::user()->name }}">
            <input type="hidden" name="last_name" value="{{ Auth::user()->surname }}">
            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="item_name" value="Purchase Balance Deposit">
            <input type="hidden" name="cancel_url" value="{{ url('user/purchase_balance/deposit') }}">
            <input type="image" src="https://www.coinpayments.net/images/pub/buynow-wide-blue.png" alt="Buy Now with CoinPayments.net">
          </form>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card bg-light">
        <a href="https://wise.com/invite/ua/sinany23" target="_blank" rel="noopener noreferrer"><img src="{{ asset('images/wise.svg') }}" class="card-img-top p-3" alt="Wise" title="Wise" /></a>
        <div class="card-body">
          <p class="card-text">wise</p>
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
