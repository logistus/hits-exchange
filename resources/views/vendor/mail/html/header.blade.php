<tr>
  <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
      @if (trim($slot) === 'Laravel')
      <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
      @else
      <a href="{{ url('/') }}"><img src="{{ asset('images/hits.exchange.svg') }}" style="width: 250px; height: 50px;" /></a>
      @endif
    </a>
  </td>
</tr>
