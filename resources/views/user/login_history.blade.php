@php
use Carbon\Carbon;
@endphp
<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/login_history') }}">Login History</a></h4>
  @if ($logins && count($logins) > 0)
  <p class="mb-0 px-1 py-3"><strong>Viewing:</strong>
    {{ $logins->firstItem() }} to {{ $logins->lastItem() }} of {{ count(Auth::user()->login_histories) }} total entries
  </p>
  <table class="table align-middle">
    <thead>
      <tr class="bg-light">
        <th scope="col">Date</th>
        <th scope="col">IP Address</th>
        <th scope="col">Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($logins as $login)
      <tr>
        <td>{{ Carbon::create($login->datetime)->format("j F Y, h:i A") }}</td>
        <td>{{ $login->ip_address }}</td>
        <td>
          @if ($login->status == 1)
          <span class='text-success'>Success</span>
          @else
          <span class='text-danger'>Invalid</span>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $logins->links() }}
  @else
  <p class="alert alert-info">You don't have any login history.</p>
  @endif
</x-layout>
