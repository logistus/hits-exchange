<x-layout title="{{ $page }}">
  <h4><a href="{{ url('start_pages') }}">Start Pages</a></h4>
  @if (count($start_pages))
  <table class="table align-middle mt-3">
    <thead>
      <tr class="bg-light">
        <th scope="col">URL</th>
        <th scope="col">Date(s)</th>
        <th scope="col">Total Views</th>
        <th scope="col">Status</th>
        <th scope="col">Delete</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($start_pages as $start_page)
      <tr>
        <td><a href="{{ $start_page->url }}" target="_blank" rel="nooepner noreferrer">{{ $start_page->url }}</a> <i class="bi-box-arrow-up-right" style="font-size: .8rem;"></i></td>
        <td>{{ $start_page->dates }}</td>
        <td>{{ $start_page->total_views }}</td>
        <td>{{ $start_page->status }}<br>
          @if ($start_page->status == "Pending Payment")
          <a href="{{ url('user/orders') }}">Make Payment</a>
          @endif</td>
        <td>
          <form action="{{ url('start_pages/delete', $start_page->id) }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-danger">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <p>You don't have any start pages.</p>
  <p><a href="{{ url('buy/start_page') }}">Click here</a> to buy.</p>
  @endif
</x-layout>
