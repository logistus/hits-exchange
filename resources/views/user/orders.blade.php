<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/orders') }}">Orders</a></h4>
  <x-alert />
  @if (count($orders) > 0)
  <table class="table table-bordered align-middle">
    <thead>
      <tr class="bg-light">
        <th scope="col">Date</th>
        <th scope="col">Type</th>
        <th scope="col">Item</th>
        <th scope="col">Status</th>
        <th scope="col">Price</th>
        <th scope="col">Payment</th>
        <th scope="col">Delete</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($orders as $order)
      <tr>
        <td>{{ $order->created_at }}</td>
        <td>{{ $order->order_type }}</td>
        <td>{{ $order->order_item }}</td>
        <td>{{ $order->status }}</td>
        <td>${{ $order->price }}</td>
        <td>
          @if ($order->status == 'Waiting Payment')
          @if (Auth::user()->purchase_balance->sum('amount') >= $order->price)
          <form action="{{ url('user/orders/pay_with_purchase_balance', $order->id) }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-primary">Pay With Purchase Balance</button>
          </form>
          @endif
          @else
          Paid
          @endif
        </td>
        <td>
          <form action="{{ url('user/orders/delete', $order->id) }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-danger">Delete Order</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <p class="alert alert-info">You don't have any orders.</p>
  @endif
</x-layout>