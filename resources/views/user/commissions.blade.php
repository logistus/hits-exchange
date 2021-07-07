@php
use App\Models\Order;
use App\Models\User;
if (!isset($_GET['type']) || $_GET['type'] == 'Unpaid')
$commissions = $commissions_unpaid;
else
$commissions = $commissions_paid;
@endphp

<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/commissions') }}">Commissions</a></h4>
  <div>Total unpaid commissions: <strong>${{ number_format(Auth::user()->commissions_unpaid->sum('amount'), 2) }}</strong></div>
  <div>Total paid commissions: <strong>${{ number_format(Auth::user()->commissions_paid->sum('amount'), 2) }}</strong></div>
  <p class="text-end">
    <strong>View by status: </strong>
    @if (request()->get('type') == '' || request()->get('type') == 'Unpaid')
    Unpaid
    @else
    <a href='?type=Unpaid'>Unpaid</a>
    @endif
    |
    @if (request()->get('type') == 'Paid')
    Paid
    @else
    <a href='?type=Paid'>Paid</a>
    @endif
  </p>
  @if (request()->get('type') == '' || request()->get('type') == 'Paid' || request()->get('type') == 'Unpaid')
  @if (count($commissions) > 0)
  <table class="table table-bordered align-middle">
    <thead>
      <tr class="bg-light">
        <th scope="col">Date</th>
        <th scope="col">Buyer</th>
        <th scope="col">Product</th>
        <th scope="col">Commission</th>
        <th scope="col">Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($commissions as $commission)
      <tr>
        <td>{{ $commission->created_at }}</td>
        <td>{{ User::where('id', Order::where('id', $commission->user_id)->value('user_id'))->value('username') }}</td>
        <td>{{ Order::where('id', $commission->order_id)->value('order_item') }}</td>
        <td>${{ $commission->amount }}</td>
        <td>{{ $commission->status }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <p class="alert alert-info">You don't have any {{ request()->get('type') == '' ? 'unpaid' : strtolower(request()->get('type')) }} commissions.</p>
  @endif
  @else
  <p class="alert alert-info">Invalid commission type.</p>
  @endif
</x-layout>