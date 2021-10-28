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
  <title>Viewing Prize Page @ {{ config('app.name') }}</title>
</head>

<body class="vh-100">
  <div class="container d-flex flex-column align-items-center mt-5 h-100">
    @if ($surfed_today > $last_prize_claimed && $surfed_today % 6 != 0)
    <h3 class="text-danger">You can't claim this prize yet, please continue surfing.</h3>
    <x-userads />
    @else
    @if ($last_prize_claimed == $surfed_today)
    <h3 class="text-danger">You already claimed this prize.</h3>
    <x-userads />
    @else
    @if (session('status'))
    <i class="bi bi-emoji-smile" style="font-size: 10rem;" class="mb-3"></i>
    <h3 class="text-success">{{ session('status') }}</h3>
    @php User::where('id', Auth::id())->update(['claim_surf_prize' => $surfed_today]); @endphp
    <x-userads />
    @else
    <h3 class="text-success">You found a prize page!</h3>
    <p>Every 25 pages, you will see this page.</p>
    <p>Click the "Claim Prize" button to see what you have won.</p>
    <form action="{{ url('claim_surf_prize') }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-success">Claim Prize</button>
    </form>
    <x-userads />
    @endif
    @endif
    @endif
  </div>
</body>

</html>
