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
          <x-coinpayments-button :amount="$pb->amount" :id="$pb->id" item="Purchase Balance Deposit" :success-url="url('user/purchase_balance')" :cancel-url="url('user/purchase_balance')" />
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
