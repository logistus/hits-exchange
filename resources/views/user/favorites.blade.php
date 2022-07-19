@php
use App\Models\Website;
use Carbon\Carbon;
@endphp
<x-layout title="{{ $page }}">
  <h4 class="my-3"><a href="{{ url('websites/favorites') }}">Favorite Websites</a></h4>
  @if($favorites && count($favorites))
  <table class="table align-middle">
    <thead>
      <tr class="text-bg-light">
        <th scope="col">Date/Time</th>
        <th scope="col">URL</th>
        <th scope="col">Delete</th>
      </tr>
      @foreach ($favorites as $favorite)
      <tr>
        <td>{{ Carbon::create($favorite->created_at)->format("j F Y, h:i A") }}</td>
        <td><a href="{{ Website::where('id', $favorite->website_id)->value('url') }}" target="_blank" rel="noopener noreferrer">{{ Website::where('id', $favorite->website_id)->value('url') }}</a></td>
        <td><a href="{{ url('websites/favorites/delete', $favorite->id) }}">Delete</a></td>
      </tr>
      @endforeach
    </thead>
  </table>
  {{ $favorites->links()}}
  @else
  <p class="alert alert-info">You don't have any favorite websites.</p>
  @endif
</x-layout>
