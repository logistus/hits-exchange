<x-layout title="{{ $page }}">
  <h4 class="mb-5"><a href="{{ url('user/purchase_balance') }}">Purchase Balance</a> > <a href="{{ url('user/transfer_commissions') }}">Transfer Commissions</a></h4>
  <x-alert />
  <p>You can transfer your unpaid commissions to your purchase balance.</p>
  <p><strong>20%</strong> bonus will be applied when you transfer. For example, if you transfer $1 you will get $1.20 to your purchase balance.</p>
  <p>You have ${{ number_format(Auth::user()->commissions_all->sum('amount'), 2) }} unpaid commissions.</p>
  <form action="{{ url('user/transfer_commissions') }}" method="POST" class="col-3 card bg-light p-3">
    @csrf
    <input type="text" name="commission_transfer_amount" id="commission_transfer_amount" placeholder="Trasnfer Amount" class="form-control">
    @error('commission_transfer_amount')
    <div class="text-danger">{{ $message }}</div>
    @enderror
    <small>Minimum amount $0.5</small>
    <button type="submit" class="btn btn-primary w-100 mt-3">Transfer</button>
  </form>
</x-layout>
