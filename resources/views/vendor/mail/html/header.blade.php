<tr>
  <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
      @if (trim($slot) === 'Laravel')
      <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
      @else
      <a href="{{ url('/') }}"><img src="http://cdn.mcauto-images-production.sendgrid.net/c4eb48fa249c9e3a/bfe90452-82c8-4ecd-8418-b9346550c7b1/91x90.png" alt="{{ config('app.name') }}" style="width: 91px; height: 90px;" /></a>
      @endif
    </a>
  </td>
</tr>
