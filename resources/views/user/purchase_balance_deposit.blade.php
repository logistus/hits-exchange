<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/purchase_balance') }}">Purchase Balance</a> > <a href="{{ url('user/purchase_balance/deposit') }}">Deposit Purchase Balance</a></h4>
  <x-alert />
  <div class="row mt-3">
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
            <input type="text" name="coinbase_deposit_amount" id="coinbase_deposit_amount" placeholder="Deposit Amount in USD" value="{{ $pb->amount }}" class="form-control" disabled>
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
          <form action="https://www.coinpayments.net/index.php" method="post" class="text-center mt-3" target="_blank">
            <input type="hidden" name="cmd" value="_pay">
            <input type="hidden" name="reset" value="1">
            <input type="hidden" name="merchant" value="0a163329f1a618ee280c49eb1db2d9c2">
            <input type="hidden" name="currency" value="USD">
            <input type="hidden" name="amountf" value="{{ $pb->amount }}">
            <input type="hidden" name="want_shipping" value="0">
            <input type="hidden" name="allow_extra" value="0">
            <input type="hidden" name="first_name" value="{{ Auth::user()->name }}">
            <input type="hidden" name="last_name" value="{{ Auth::user()->surname }}">
            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="custom" value="{{ $pb->id  }}">
            <input type="hidden" name="item_name" value="Purchase Balance Deposit">
            <input type="hidden" name="cancel_url" value="{{ url('user/purchase_balance/deposit') }}">
            <input type="hidden" name="success_url" value="{{ url('user/purchase_balance') }}">
            <input type="hidden" name="ipn_url" value="{{ url('buy/ipn') }}">
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
</x-layout>
