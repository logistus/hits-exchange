<x-layout title="{{ $page }}">
  <h4><a href="{{ url('start_pages') }}">Start Pages</a></h4>
  @if (count($start_pages))
  <table class="table table-bordered align-middle">
    <thead>
      <tr class="bg-light">
        <th scope="col">URL</th>
        <th scope="col">Date(s)</th>
        <th scope="col">Total Views</th>
        <th scope="col">Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($start_pages as $start_page)
      <tr>
        <td><a href="{{ $start_page->url }}" target="_blank" rel="nooepner noreferrer">{{ $start_page->url }}</a> <i class="bi-box-arrow-up-right" style="font-size: .8rem;"></i></td>
        <td>{{ $start_page->dates }}</td>
        <td>{{ $start_page->total_views }}</td>
        <td>{{ $start_page->status }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <p class="text-center">You don't have any start pages.</p>
  <p class="text-center"><a href="{{ url('buy/start_page') }}">Click here</a> to buy.</p>
  @endif
</x-layout>
