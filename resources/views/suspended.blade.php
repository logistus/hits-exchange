<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <title>{{ config('app.name') }} - Suspended</title>
</head>

<body class="bg-light" style="height: 100vh;">
  <div class="d-flex flex-column justify-content-center align-items-center h-100">
    <span style="font-size: 3rem;">🚨</span>
    <p>Your account has been suspended
      @if (Auth::user()->suspend_until)
      until {{ Auth::user()->suspend_until }}
      @endif
      .</p>
    @if (Auth::user()->suspend_reason)
    <p><strong>Reason: </strong> {{ Auth::user()->suspend_reason }}</p>
    @endif

    <p>Click <a href="{{ url('support') }}">here</a> to open a ticket if you think this is a mistake.</p>
  </div>

</body>

</html>