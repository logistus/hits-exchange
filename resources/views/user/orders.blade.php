<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/orders') }}">Orders</a></h4>
  @if (count($orders) > 0)
  <table class="table table-bordered align-middle">
    <thead>
      <tr class="bg-light">
        <th scope="col">Date</th>
        <th scope="col">Type</th>
        <th scope="col">Item</th>
        <th scope="col">Status</th>
        <th scope="col">Price</th>
        <th scope="col">Actions</th>
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
          <!--
          <form action="https://www.coinpayments.net/index.php" method="post" target="_top">
            <input type="hidden" name="cmd" value="_pay">
            <input type="hidden" name="reset" value="1">
            <input type="hidden" name="want_shipping" value="0">
            <input type="hidden" name="merchant" value="0a163329f1a618ee280c49eb1db2d9c2">
            <input type="hidden" name="currency" value="LTC">
            <input type="hidden" name="amountf" value="{{ $order->price }}">
            <input type="hidden" name="item_name" value="{{ $order->order_item }}">
            <input type="hidden" name="allow_extra" value="0">
            <input type="hidden" name="success_url" value="http://localhost:8000/user/orders">
            <input type="hidden" name="cancel_url" value="http://localhost:8000/user/orders">
            <input type="image" src="https://www.coinpayments.net/images/pub/buynow-grey.png" alt="Buy Now with CoinPayments.net">
          </form>
          -->
          @if ($order->status == 'Waiting Payment')
          <button type="button" class="btn btn-success">Make Payment</button>
          <form action="{{ url('user/orders/delete', $order->id) }}" method="POST" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-danger">Cancel Order</button>
          </form>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <p class="alert alert-info">You don't have any orders.</p>
  @endif
</x-layout>