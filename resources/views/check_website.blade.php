<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
  <link href="{{ url('css/app.css') }}" rel="stylesheet">
  <title>Check Website @ {{ config("app.name") }}</title>
</head>

<body class="vh-100">
  <div class="d-flex flex-column h-100">
    <div style="height: 110px;" class="bg-light d-flex flex-column justify-content-center align-items-center">
      <div>Can you see your website below?</div>
      <form action="{{ url('websites/approve', $website->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-link">Yes</button>
      </form>
      <a href="{{ url('websites') }}" class="d-block">No Semantic</a>
    </div>
    <iframe style="height: calc(100% - 110px); overflow-x:hidden;" frameborder="0" src="{{ $website->url }}" id="surf_window"></iframe>
  </div>
</body>
</html>
