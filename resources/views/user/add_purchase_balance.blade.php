<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/purchase_balance') }}">Purchase Balance</a> > <a href="{{ url('user/add_purchase_balance') }}">Add Purchase Balance</a></h4>
  <form action="{{ url('user/purchase_balance/create') }}" method="POST">
    @csrf
    <p>How much USD do you want to add?</p>
    <div class="col-3">
      <input type="number" name="deposit_amount" id="deposit_amount" class="form-control">
    </div>
    @error('deposit_amount')
    <div class="text-danger">{{ $message }}</div>
    @enderror
    <button type="submit" class="btn btn-primary mt-3">Deposit</button>
  </form>
</x-layout>
