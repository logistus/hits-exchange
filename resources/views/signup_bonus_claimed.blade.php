@php
use App\Models\User;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
  <link href="{{ url('css/app.css') }}" rel="stylesheet">
  <title>Signup Bonus Claimed @ {{ config('app.name') }}</title>
</head>

<body class="vh-100">
  <div class="container d-flex flex-column align-items-center mt-5 h-100">
    <h3 class="text-success">Signup Bonus Claimed!</h3>
    <h4>You have won {{ $signup_bonus->bonus_type == "Purchase Balance" ? "$" : "" }}{{ $signup_bonus->bonus_amount }} {{ $signup_bonus->bonus_type }}</h4>
    <x-userads />
  </div>
</body>

</html>
