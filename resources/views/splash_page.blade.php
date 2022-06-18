<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config('app.name') }}</title>
  <style>
    .invited {
      position: absolute;
      bottom: 25px;
      right: 25px;
      border: 1px solid #dadada;
      background: #cacaca;
      padding: 10px 20px;
      font-family: Verdana;
      z-index: 2;
      text-align: center;
      border-radius: 10px;
    }

    .invited p {
      font-weight: bold;
    }

  </style>
</head>

<body>
  <div class="invited">
    <img src="{{ $user->gravatar() }}" alt="{{ $user->username }}" />
    <p>Invited by {{ $user->fullName }}</p>
  </div>
  {!! $html_codes !!}
</body>

</html>
