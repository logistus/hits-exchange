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
  <title>Login Spotlight @ {{ config("app.name") }}</title>
</head>

<body class="vh-100">
  <div class="d-flex flex-column h-100">
    <div style="height: 110px;" class="bg-light d-flex align-items-center justify-content-between">
      <div class="ms-5" id="status"></div>
      <a href="{{ url('buy/login_spotlight') }}" target="blank" class="me-5 fw-bold">Buy Login Spotlight Page</a>
    </div>
    <div style="height: 10px;" id="timer_wrapper" class="bg-transparent w-100">
      <div id="progress-bar" class="bg-success h-100" style="width: 0%;"></div>
    </div>
    <iframe style="height: calc(100% - 110px); overflow-x:hidden;" frameborder="0" src="{{ $login_spotlight_url }}" id="surf_window"></iframe>
  </div>
</body>
<script src="{{ asset('js/jquery-3.6.0.js') }}"></script>

<script>
  function checkScreenSize() {
    // do not allow surf on small screens (below 800x600)
    if (window.innerWidth < 800 || window.innerHeight < 600) {
      $("body").empty();
      $("body").html("<div class='h-100 d-flex justify-content-center align-items-center'><p class='text-center'>Surfing on small screens is not possible at this moment.<br><a href='/'>Click here</a> to back to dashboard.</p></div>");
    }
  }


  $(function() {
    checkScreenSize();
    var timer = 10;

    $("#status").html("Viewing login spotlight - <a href='/dashboard'>Skip</a>");

    function startProgressBar() {
      $("#progress-bar").attr("style", "width: 0%");
      $("#progress-bar").animate({
        width: "100%"
      }, {
        duration: (timer * 1) * 1000
        , easing: "linear"
        , complete: function() {
          $.post('/login_spotlight', {
              _token: $('meta[name="csrf-token"]').attr('content')
            }
            , function(response) {
              $("#status").html(response.status + " - <a href='/dashboard'>Go to dashboard</a>");
            });
        }
      });
    }
    startProgressBar();

  });

</script>

</html>
