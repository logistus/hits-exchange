@php
use App\Models\Order;
@endphp
<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/purchase_balance') }}">Purchase Balance</a></h4>
  <x-alert />
  <div class="d-flex justify-content-between align-items-center mb-5">
    <div>You have <strong>${{ number_format(Auth::user()->purchase_balance_completed->sum('amount'), 2) }}</strong> in your purchase balance. You <strong>can't</strong> withdraw this money. You can use it to purchase only.</div>
    <div>
      <a href="{{ url('user/transfer_commissions') }}" class="btn btn-primary">Transfer Commissions</a>
      <a href="{{ url('user/purchase_balance/create') }}" class="btn btn-success">Add</a>
    </div>
  </div>
  @if (count($purchase_balance) > 0)
  <table class="table align-middle">
    <thead>
      <tr class="bg-light">
        <th scope="col">Date</th>
        <th scope="col">Type</th>
        <th scope="col">Product</th>
        <th scope="col">Amount</th>
        <th scope="col">Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($purchase_balance as $pb)
      <tr>
        <td>{{ $pb->created_at }}</td>
        <td>{{ $pb->type }}</td>
        <td>
          @if ($pb->type == "Purchase")
          {{ Order::where('id', $pb->order_id)->value('order_item') }}
          @else
          N/A
          @endif
        </td>
        <td>
          <span class="{{ $pb->amount > 0 ? 'text-success' : 'text-danger' }}">
            ${{ $pb->amount }}
          </span>
        </td>
        <td>
          {{ $pb->status }}
          @if ($pb->status == 'Pending')
          (<a href="{{ url('user/purchase_balance/deposit', $pb->id) }}">Deposit</a> - <a href="{{ url('user/purchase_balance/delete', $pb->id) }}">Delete</a>)
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <p class="alert alert-info">You don't have any purchase balance transactions.</p>
  @endif
</x-layout>
