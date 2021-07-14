<x-layout title="{{ $page }}">
  <h4><a href="{{ url('promote') }}">Promo Tools</a></h4>
  <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Architecto vero doloribus natus nihil adipisci quam illum accusamus, cum, dolorum repellat iste. Dolore, earum voluptates! Earum nisi porro doloribus laudantium quisquam.</p>
  <h5>Main Affilite Link</h5>
  <a href="{{ config('app.url') }}/ref/{{ Auth::id() }}/" target="_blank">{{ config('app.url') }}/ref/{{ Auth::id() }}/</a>
  <h5 class="mt-3">Splash Pages</h5>
  <h5 class="mt-3">Banners</h5>
</x-layout>
