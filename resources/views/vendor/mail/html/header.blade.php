<tr>
  <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
      @if (trim($slot) === 'Laravel')
      <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
      @else
      <img src="{{ asset('images/hits.exchange.logo.png') }}" alt="{{ config('app.name') }}" style="width: 91px; height: 90px;" />
      @endif
    </a>
  </td>
</tr>
