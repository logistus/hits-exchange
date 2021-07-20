@php
use App\Models\Banner;
use App\Models\SquareBanner;
use App\Models\TextAd;

$banner = Banner::select_random();
$text = TextAd::select_random();
$text2 = TextAd::select_random();
$square_banner = SquareBanner::select_random();
$square_banner2 = SquareBanner::select_random();
@endphp
<div class=" mt-3 mx-auto bd-highlight p-2 d-inline-flex align-items-center flex-column flex-lg-row bg-light">
  <div class="mb-2">
    <a href="{{ url('square_banners/click', $square_banner->id) }}" target="_blank" rel="noopener noreferrer">
      <img src="{{ $square_banner->image_url }}" class="w-100" />
    </a>
  </div>
  <div class="d-flex flex-column mx-2">
    <a href="{{ url('banners/click', $banner->id) }}" target="_blank" rel="noopener noreferrer">
      <img src="{{ $banner->image_url }}" class="w-100" />
    </a>
    <a href="{{ url('texts/click', $text->id) }}" class="p-1 text-center mt-1 text" target="_blank" rel="noopener noreferrer" style="
          text-decoration: none;
          color: {{ $text->text_color }};
          background-color: {{ $text->bg_color }};
          font-weight: {{ $text->text_bold ? 'bold' : 'normal' }};
        ">{{ $text->body }}</a>
    <a href="{{ url('texts/click', $text2->id) }}" class="p-1 text-center mt-1 text" target="_blank" rel="noopener noreferrer" style="
          text-decoration: none;
          color: {{ $text2->text_color }};
          background-color: {{ $text2->bg_color }};
          font-weight: {{ $text2->text_bold ? 'bold' : 'normal' }};
        ">{{ $text2->body }}</a>
  </div>
  <div class="mt-2 order-lg-3">
    <a href="{{ url('square_banners/click', $square_banner2->id) }}" target="_blank" rel="noopener noreferrer">
      <img src="{{ $square_banner2->image_url }}" width="125" height="125" />
    </a>
  </div>
</div>
