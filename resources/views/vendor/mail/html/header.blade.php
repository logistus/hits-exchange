<tr>
  <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
      @if (trim($slot) === 'Laravel')
      <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
      @else
      <img src="https://laravel.com/img/notification-logo.png" alt="{{ config('app.name') }}" style="width: 250px; height: 50px;" />
      @endif
    </a>
  </td>
</tr>
