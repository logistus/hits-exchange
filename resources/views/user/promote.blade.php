<x-layout title="{{ $page }}">
  <h4><a href="{{ url('promote') }}">Promo Tools</a></h4>
  <p>Do you want to earn commissions? Then, this page is where you want to be.</p>
  <h5>Main Affilite Link</h5>
  <a href="{{ config('app.url') }}/ref/{{ request()->user()->username }}/" target="_blank">{{ config('app.url') }}/ref/{{ request()->user()->username }}/</a>
  <h5 class="mt-3">Splash Pages</h5>
  @foreach ($splash_pages as $splash_page)
  <a href="{{ config('app.url') }}/splash/{{ $splash_page->id }}/ref/{{ request()->user()->username }}/" target="_blank">
    {{ config('app.url') }}/splash/{{ $splash_page->id }}/ref/{{ request()->user()->username }}/
  </a>
  @endforeach
  <h5 class="mt-3">Banners</h5>
</x-layout>
