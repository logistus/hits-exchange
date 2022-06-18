<x-layout title="{{ $page }}">
  <h4><a href="{{ url('buy/credits') }}">Buy Credits & Impressions</a></h4>
  <x-alert />
  <div class="d-flex flex-wrap my-3">
    @foreach ($credit_ad_prices as $credit_ad_price)
    <div class="m-3 bg-light d-flex flex-column align-items-center flex-fill fs-4 border border-2 p-3" style="gap: 25px;">
      <div class="fw-bold text-center">{{ number_format($credit_ad_price->ad_amount) }} Credits</div>
      <div class="fw-bold text-success">${{ $credit_ad_price->price }}</div>
      <small class="fs-6 text-muted">price per credit ${{ $credit_ad_price->price / $credit_ad_price->ad_amount }}</small>
      <form action="{{ url('buy/credits', $credit_ad_price->id) }}" method="POST" class="text-center">
        @csrf
        <button type="submit" class="btn btn-warning">Place Order</button>
      </form>
    </div>
    @endforeach
  </div>
  <div class="d-flex flex-wrap mb-3">
    @foreach ($banner_ad_prices as $banner_ad_price)
    <div class="m-3 bg-light d-flex flex-column align-items-center flex-fill fs-4 border border-2 p-3" style="gap: 25px;">
      <div class="fw-bold text-center">{{ number_format($banner_ad_price->ad_amount) }} Banner Impressions</div>
      <div class="fw-bold text-success">${{ $banner_ad_price->price }}</div>
      <form action="{{ url('buy/credits', $banner_ad_price->id) }}" method="POST" class="text-center">
        @csrf
        <button type="submit" class="btn btn-warning">Place Order</button>
      </form>
    </div>
    @endforeach
  </div>
  <div class="d-flex flex-wrap mb-3">
    @foreach ($square_banner_ad_prices as $square_banner_ad_price)
    <div class="m-3 bg-light d-flex flex-column align-items-center flex-fill fs-4 border border-2 p-3" style="gap: 25px;">
      <div class="fw-bold text-center">{{ number_format($square_banner_ad_price->ad_amount) }} Square Banner Impressions</div>
      <div class="fw-bold text-success">${{ $square_banner_ad_price->price }}</div>
      <form action="{{ url('buy/credits', $square_banner_ad_price->id) }}" method="POST" class="text-center">
        @csrf
        <button type="submit" class="btn btn-warning">Place Order</button>
      </form>
    </div>
    @endforeach
  </div>
  <div class="d-flex flex-wrap mb-3">
    @foreach ($text_ad_prices as $text_ad_price)
    <div class="m-3 bg-light d-flex flex-column align-items-center flex-fill fs-4 border border-2 p-3" style="gap: 25px;">
      <div class="fw-bold text-center">{{ number_format($text_ad_price->ad_amount) }} Text Ad Impressions</div>
      <div class="fw-bold text-success">${{ $text_ad_price->price }}</div>
      <form action="{{ url('buy/credits', $text_ad_price->id) }}" method="POST" class="text-center">
        @csrf
        <button type="submit" class="btn btn-warning">Place Order</button>
      </form>
    </div>
    @endforeach
  </div>
</x-layout>
