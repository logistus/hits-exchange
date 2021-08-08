<x-layout title="{{ $page }}">
  <h4><a href="{{ url('buy/credits') }}">Buy Credits & Impressions</a></h4>
  <x-alert />
  <h5 class="mt-3 text-primary text-center fw-bold">Buy Credits</h5>
  <div class="d-flex justify-content-center">
    @foreach ($credit_ad_prices as $credit_ad_price)
    <div class="card me-3 bg-light">
      <div class="card-body">
        <p class="card-title text-center">{{ $credit_ad_price->ad_amount }} Credits</p>
        <h5 class="card-text text-center">${{ $credit_ad_price->price }}</h5>
        <form action="{{ url('buy/credits', $credit_ad_price->id) }}" method="POST" class="text-center">
          @csrf
          <button type="submit" class="btn btn-warning">Place Order</button>
        </form>
      </div>
    </div>
    @endforeach
  </div>
  <h5 class="mt-3 text-primary text-center fw-bold">Buy Banner Impressions</h5>
  <div class="d-flex justify-content-center">
    @foreach ($banner_ad_prices as $banner_ad_price)
    <div class="card me-3 bg-light">
      <div class="card-body">
        <p class="card-title text-center">{{ $banner_ad_price->ad_amount }} Banner Impressions</p>
        <h5 class="card-text text-center">${{ $banner_ad_price->price }}</h5>
        <form action="{{ url('buy/credits', $banner_ad_price->id) }}" method="POST" class="text-center">
          @csrf
          <button type="submit" class="btn btn-warning">Place Order</button>
        </form>
      </div>
    </div>
    @endforeach
  </div>
  <h5 class="mt-3 text-primary text-center fw-bold">Buy Square Banner Impressions</h5>
  <div class="d-flex justify-content-center">
    @foreach ($square_banner_ad_prices as $square_banner_ad_price)
    <div class="card me-3 bg-light">
      <div class="card-body">
        <p class="card-title text-center">{{ $square_banner_ad_price->ad_amount }} Square Banner Impressions</p>
        <h5 class="card-text text-center">${{ $square_banner_ad_price->price }}</h5>
        <form action="{{ url('buy/credits', $square_banner_ad_price->id) }}" method="POST" class="text-center">
          @csrf
          <button type="submit" class="btn btn-warning">Place Order</button>
        </form>
      </div>
    </div>
    @endforeach
  </div>
  <h5 class="mt-3 text-primary text-center fw-bold">Buy Text Ad Impressions</h5>
  <div class="d-flex justify-content-center">
    @foreach ($text_ad_prices as $text_ad_price)
    <div class="card me-3 bg-light">
      <div class="card-body">
        <p class="card-title text-center">{{ $text_ad_price->ad_amount }} Text Ad Impressions</p>
        <h5 class="card-text text-center">${{ $text_ad_price->price }}</h5>
        <form action="{{ url('buy/credits', $text_ad_price->id) }}" method="POST" class="text-center">
          @csrf
          <button type="submit" class="btn btn-warning">Place Order</button>
        </form>
      </div>
    </div>
    @endforeach
  </div>
</x-layout>
