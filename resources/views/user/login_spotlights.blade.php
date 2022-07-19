<x-layout title="{{ $page }}">
  <h4><a href="{{ url('login_spotlights') }}">Login Spotlights</a></h4>
  @if (count($login_spotlights))
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
      @foreach ($login_spotlights as $login_spotlight)
      <tr>
        <td><a href="{{ $login_spotlight->url }}" target="_blank" rel="nooepner noreferrer">{{ $login_spotlight->url }}</a> <i class="bi-box-arrow-up-right" style="font-size: .8rem;"></i></td>
        <td>{{ $login_spotlight->dates }}</td>
        <td>{{ $login_spotlight->total_views }}</td>
        <td>{{ $login_spotlight->status }}<br>
          @if ($login_spotlight->status == "Pending Payment")
          <a href="{{ url('user/orders') }}">Make Payment</a>
          @endif
        </td>
        <td>
          <form action="{{ url('login_spotlights/delete', $login_spotlight->id) }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-danger">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <p>You don't have any login spotlighrs.</p>
  <p><a href="{{ url('buy/login_spotlight') }}">Click here</a> to buy.</p>
  @endif
</x-layout>
