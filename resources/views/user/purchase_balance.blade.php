@php
use App\Models\Order;
use Carbon\Carbon;
@endphp
<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/purchase_balance') }}">Purchase Balance</a></h4>
  <x-alert />
  @error('commission_transfer_amount')
  <div class="text-danger">{{ $message }}</div>
  @enderror
  <div class="d-flex justify-content-between align-items-center mb-5">
    <div>You have <strong>${{ number_format(Auth::user()->purchase_balance_completed->sum('amount'), 2) }}</strong> in your purchase balance. You <strong>can't</strong> withdraw this money. You can use it to purchase only.</div>
    <div>
      <a href="#" data-bs-toggle="modal" data-bs-target="#transferCommissionsModal" class="btn btn-primary">Transfer Commissions</a>
      {{-- <a href="{{ url('user/purchase_balance/create') }}" class="btn btn-success">Add</a> --}}
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
        <td>{{ Carbon::create($pb->created_at)->format("F j, Y, g:i a") }}</td>
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
  <!-- Transfer Commissions Modal -->
  <div class="modal fade" id="transferCommissionsModal" tabindex="-1" aria-labelledby="Transfer Commissions Modal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Transfer Commissions</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ url('user/transfer_commissions') }}" method="POST">
          <div class="modal-body">
            <!-- // TODO: must be able to set 20% from admin page -->
            <p><strong>20%</strong> bonus will be applied when you transfer. For example, if you transfer $1 you will get $1.20 to your purchase balance.</p>
            <p>You have ${{ number_format(Auth::user()->commissions_all->sum('amount'), 2) }} unpaid commissions.</p>
            @csrf
            <input type="number" max="{{ number_format(Auth::user()->commissions_all->sum('amount'), 2) }}" name="commission_transfer_amount" id="commission_transfer_amount" placeholder="Trasnfer Amount" class="form-control" required>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Transfer</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-layout>
