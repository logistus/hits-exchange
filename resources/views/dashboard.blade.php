<x-layout title="{{ $page }}">
  <p>Welcome back {{ Auth::user()->name }}</p>
  <p>Surf Timer: {{ Auth::user()->type->surf_timer }} </p>
  <p>Credit per Click: {{ Auth::user()->type->surf_ratio }} </p>
  <p>Credits: {{ Auth::user()->credits }} </p>
</x-layout>