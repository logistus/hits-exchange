<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <title>Logi TE {{ $title ? "- $title" : "" }}</title>
</head>

<body class="bg-light">
  <div class="container-lg">
    <div class="row">
      <div class="col d-flex justify-content-between align-items-center my-3">
        <x-logo />
        @auth
        <div>
          <div class="d-flex align-items-center">
            <a href="{{ url('private_messages') }}" title="Private Messages" class="mx-4" style="text-decoration: none;">
              <i class="bi-envelope{{ count(Auth::user()->unread_private_messages) > 0 ? '' : '-open' }}" style="font-size: 1.5rem;"></i>
              @if (count(Auth::user()->unread_private_messages) > 0)
              <span class="badge bg-danger" style="position: relative; bottom: 15px;">{{ count(Auth::user()->unread_private_messages) }}</span>
              @endif
            </a>
            <img src=" {{ Auth::user()->generate_gravatar(Auth::user()->id) }}" alt="{{ Auth::user()->username }}" height="48" class="rounded-circle">
            <div class="dropdown">
              <a class="btn dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="text-start" style="width: 100px; overflow-wrap: break-word; word-wrap:break-word; white-space:normal;">{{ Auth::user()->username }}</div>
                <div @if (Auth::user()->type->name == "Free")
                  class="badge bg-secondary"
                  @else
                  class="badge bg-danger"
                  @endif
                  >{{ Auth::user()->type->name }} Member
                </div>
              </a>
              <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                <li><a class="dropdown-item" href="{{ url('user/profile') }}">My Profile</a></li>
                <li><a class="dropdown-item" href="{{ url('user/referrals') }}">My Referrals</a></li>
                <li><a class="dropdown-item" href="{{ url('user/commissions') }}">My Commissions</a></li>
                <li><a class="dropdown-item" href="{{ url('user/orders') }}">My Orders</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="{{ url('logout') }}">Logout</a></li>
              </ul>
            </div>
          </div>
        </div>
        @endauth
        @guest
        <div>
          <a href="{{ url('login') }}" class="me-3">Login</a>
          <a href="{{ url('register') }}" class="btn btn-primary">Register</a>
        </div>
        @endguest
      </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
      <div class="container-fluid px-0">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              @if (Auth::check())
              <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
              @else
              <a class="nav-link" href="{{ url('/') }}">Home</a>
              @endif
            </li>
            @auth
            <li class="nav-item"><a class="nav-link" href="{{ url('surf') }}">Surf</a></li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="rewardsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Rewards
              </a>
              <ul class="dropdown-menu" aria-labelledby="rewardsDropdown">
                <li><a class="dropdown-item" href="{{ url('surf_codes') }}">Surf Codes</a></li>
                <li><a class="dropdown-item" href="{{ url('surfer_rewards') }}">Surfer Rewards</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="adsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                My Ads
              </a>
              <ul class="dropdown-menu" aria-labelledby="adsDropdown">
                <li><a class="dropdown-item" href="{{ url('websites') }}">My Websites</a></li>
                <li><a class="dropdown-item" href="{{ url('banners') }}">My Banners</a></li>
                <li><a class="dropdown-item" href="{{ url('square_banners') }}">My Square Banners</a></li>
                <li><a class="dropdown-item" href="{{ url('texts') }}">My Text Ads</a></li>
                <li><a class="dropdown-item" href="{{ url('start_pages') }}">My Start Pages</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="creditsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Credits
              </a>
              <ul class="dropdown-menu" aria-labelledby="creditsDropdown">
                <li><a class="dropdown-item" href="{{ url('convert') }}">Convert Credits</a></li>
                <li><a class="dropdown-item" href="{{ url('websites/auto_assign') }}">Auto Assign</a></li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="buyAdsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Buy Advertising
              </a>
              <ul class="dropdown-menu" aria-labelledby="buyAdsDropdown">
                <li><a class="dropdown-item" href="{{ url('buy/start_page') }}">Start Page</a></li>
                <li><a class="dropdown-item" href="{{ url('buy/login_spotlight') }}">Login Spotlight</a></li>
                <li><a class="dropdown-item" href="{{ url('buy/credits') }}">Buy Credits</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url('promote') }}">Promo Tools</a>
            </li>
            </li>
            @endauth
            <li class="nav-item">
              <a class="nav-link" href="#">Support</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="card p-3">
      {{ $slot }}
    </div>
    <footer class="bg-dark text-white p-3">
      footer
    </footer>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  <script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
  </script>
</body>

</html>