<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
  <link href="{{ url('css/app.css') }}" rel="stylesheet">
  <title>Surfing @ {{ config("app.name") }}</title>
</head>

<body class="vh-100">
  <div class="d-flex flex-column h-100 bg-light">
    <div style="height: 110px;" class="d-flex align-items-center justify-content-between col-9 mx-auto">
      <div class="ms-3 invisible mx-5 rounded-3" id="website-owner" style="min-width: 155px; max-height: 66px;">
        <div class="d-flex align-items-center justify-content-center">
          <img src="" id="gravatar" alt="" class="me-3" width="48" height="48" />
          <span id="owner"></span>
        </div>
      </div>
      <div class="d-flex align-items-center">
        <div style="min-width: 40px; text-align: center;">
          <div class="timer fs-1 me-3"></div>
        </div>
        <div style="min-width: 330px;">
          <div id="status" style="min-width: 330px; min-height: 66px; line-height: 66px;" class="d-none fs-2 text-center"></div>
          <form method="POST" id="validate_view" class="d-none">
            @csrf
            <div class="px-1 py-2 icons border bg-white" style="min-width: 320px; min-height: 66px;"></div>
          </form>
        </div>
        <div class="border bg-white text-center px-3" style="padding-top: 6px; min-height: 66px;">
          <div>Surfed Today</div>
          <strong id="surfed_today">{{ Auth::user()->surfed_today }}</strong>
        </div>
      </div>
      <div class="me-2 d-flex flex-column">
        <a href="{{ url('banners/click', session('selected_banner_id')) }}" id="banner_target" target="_blank" rel="noopener noreferrer">
          <img src="{{ session('selected_banner_image') }}" id="banner_image" width="468" height="60" />
        </a>
        <a href="{{ url('texts/click', session('selected_text_id')) }}" class="p-1 text-center my-1 text" target="_blank" rel="noopener noreferrer" style="
          text-decoration: none;
          color: {{ session('selected_text_color') }};
          background-color: {{ session('selected_bg_color') }};
          font-weight: {{ session('selected_text_bold') ? 'bold' : 'normal' }};
        ">{{ session('selected_text_body') }}</a>
      </div>
    </div>
    <div class="bg-light p-2 fs-6 d-flex justify-content-between align-items-center px-3 border-bottom">
      <div class="d-flex">
        <a href="{{ url('/buy/credits') }}" target="_blank" class="me-3">Buy Credits</a>
        <a href="{{ url('/dashboard') }}" class="me-3">Dashboard</a>
      </div>
      <div class="btn-group bg-white" role="group" aria-label="Actions for the website currently viewing">
        <a href="#" class="btn btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Add to Favorites">
          <i class="bi bi-star"></i>
        </a>
        <a href="#" id="report_url" target="_blank" class="btn btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Report this URL">
          <i class="bi bi-exclamation-triangle"></i>
        </a>
        <a href="{{ session('selected_website_url') }}" id="url" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Open In New Tab">
          <i class="bi bi-box-arrow-up-right"></i>
        </a>
      </div>
      <div>Server Time: {{ date("Y-m-d") }}</div>
    </div>
    <iframe style="height: calc(100% - 150px); overflow-x:hidden;" frameborder="0" src="{{ session('selected_website_url') }}" id="surf_window"></iframe>
  </div>
</body>
<script src="{{ asset('js/jquery-3.6.0.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

<script>
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })

  let cid;

  function checkScreenSize() {
    // do not allow surf on small screens (below 800x600)
    if (window.innerWidth < 800 || window.innerHeight < 600) {
      $("body").empty();
      $("body").html("<div class='h-100 d-flex justify-content-center align-items-center'><p class='text-center'>Surfing on small screens is not possible at this moment.<br><a href='/'>Click here</a> to back to dashboard.</p></div>");
    }
  }

  function validateClick(id) {
    checkScreenSize();
    cid = id;
    $("#validate_view").submit();
  }

  $(function() {
    checkScreenSize();

    var timer = "{{ Auth::user()->type->surf_timer }}";
    var app_url = "{{ config('app.url') }}";
    var start_url = "{{ session('selected_website_url') }}";
    var surfed_session = "{{ session('surfed_session') }}";

    if (start_url.startsWith(app_url) || surfed_session == "0") {
      $("#website-owner").removeClass("visible").addClass("invisible");
    } else {
      $("#website-owner").removeClass("invisible").addClass("visible");
    }

    function startProgressBar() {
      $(".icons").html('<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
      $("#validate_view").addClass("d-none");
      $(".timer").removeClass("d-none").text(timer);
      $("#status").removeClass("d-none");

      let timerDown = setInterval(countDown, 1000);

      function countDown() {
        if ($(".timer").text() * 1 > 0) {
          $(".timer").text(($(".timer").text()) - 1);
          $("#status").removeClass("d-none");
        } else {
          $("#validate_view").removeClass("d-none");
          $(".icons").load('/view_surf_icons');
          $(".timer").addClass("d-none");
          $("#status").addClass("d-none");
          clearInterval(timerDown);
        }
      }
    }

    startProgressBar();

    $("#validate_view").submit(function(e) {
      e.preventDefault();

      $.post("/validate_click/" + cid, {
        _token: $("[name='_token']").val()
      , }, function(response) {
        if (response.status == "ec") {
          location.href = "/";
        } else {
          //console.log(response);
          $("#status").removeClass("d-none").html(response.status);
          $("#surf_window").attr("src", response.url);
          $("#url").attr("href", response.url);
          $("#owner").text(response.website_owner_username);
          $("#gravatar").prop("src", response.website_owner_gravatar).prop("alt", response.website_owner_username);

          $("#banner_image").attr("src", response.banner_image);
          $("#banner_target").attr("href", "/banners/click/" + response.banner_id);

          $(".text").text(response.text_body);
          $(".text").attr("href", "/texts/click/" + response.text_id);
          $(".text").css("color", response.text_color);
          $(".text").css("background-color", response.text_bg_color);
          if (response.text_bold) {
            $(".text").css("font-weight", "bold");
          } else {
            $(".text").css("font-weight", "normal");
          }

          $("#surfed_session").text(parseInt($("#surfed_session").text()) + 1);
          $("#surfed_today").text(response.surfed_today);
          credits = response.credits * 1;
          $("#credits").text(credits.toFixed(2));
          $("#report_url").attr("href", "/report_website/" + response.website_id);
          if (response.url.startsWith(app_url)) {
            $("#website-owner").removeClass("visible").addClass("invisible");
          } else {
            $("#website-owner").removeClass("invisible").addClass("visible");
          }

          startProgressBar();
        }
      });

    });
  });

</script>

</html>
