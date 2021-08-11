<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }} Admin - @yield('page', '')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('/css/OverlayScrollbars.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
  @yield('stylesheets')
</head>

<body class="hold-transition sidebar-mini skin-mini layout-fixed">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a href="{{ url('/') }}" target="_blank" class="nav-link">View Site <i class="fas fa-external-link-alt"></i></a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            {{ Auth::user()->username }}
            <i class="fas fa-chevron-down"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
            <a href="{{ url('logout') }}" class="dropdown-item">
              <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light-primary elevation-4">
      <!-- Brand Logo -->
      <a href="{{ url('admin') }}" class="brand-link">
        <img src="{{ asset('images/hits.exchange.logo.png') }}" alt="{{ config('app.name') }}" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="{{ url('admin') }}" class="nav-link {{ url()->current() == url('admin') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item {{ (url()->current() == url('admin/members/list') || url()->current() == url('admin/members/add')) ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ (url()->current() == url('admin/members/list') || url()->current() == url('admin/members/add')) ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <p>
                  Members
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ url('admin/members/add') }}" class="nav-link {{ url()->current() == url('admin/members/add') ? 'active' : '' }}">
                    <p>Add Member</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ url('admin/members/list') }}" class="nav-link {{ url()->current() == url('admin/members/list') ? 'active' : '' }}">
                    <p>List Members</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item {{ (url()->current() == url('admin/websites/list') || url()->current() == url('admin/websites/add')) ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ (url()->current() == url('admin/websites/list') || url()->current() == url('admin/websites/add')) ? 'active' : '' }}">
                <i class="fas fa-link"></i>
                <p>
                  Websites
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ url('admin/websites/add') }}" class="nav-link {{ url()->current() == url('admin/websites/add') ? 'active' : '' }}">
                    <p>Add Website</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ url('admin/websites/list') }}" class="nav-link {{ url()->current() == url('admin/websites/list') ? 'active' : '' }}">
                    <p>List Websites</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item {{ (url()->current() == url('admin/banners/list') || url()->current() == url('admin/banners/add')) ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ (url()->current() == url('admin/banners/list') || url()->current() == url('admin/banners/add')) ? 'active' : '' }}">
                <i class="fas fa-images"></i>
                <p>
                  Banners
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ url('admin/banners/add') }}" class="nav-link {{ url()->current() == url('admin/banners/add') ? 'active' : '' }}">
                    <p>Add Banner</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ url('admin/banners/list') }}" class="nav-link {{ url()->current() == url('admin/banners/list') ? 'active' : '' }}">
                    <p>List Banners</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item {{ (url()->current() == url('admin/square_banners/list') || url()->current() == url('admin/square_banners/add')) ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ (url()->current() == url('admin/square_banners/list') || url()->current() == url('admin/square_banners/add')) ? 'active' : '' }}">
                <i class="fas fa-photo-video"></i>
                <p>
                  Square Banners
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ url('admin/square_banners/add') }}" class="nav-link {{ url()->current() == url('admin/square_banners/add') ? 'active' : '' }}">
                    <p>Add Square Banner</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ url('admin/square_banners/list') }}" class="nav-link {{ url()->current() == url('admin/square_banners/list') ? 'active' : '' }}">
                    <p>List Square Banners</p>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>@yield('page', '')</h1>
            </div>
            <div class="col-sm-6">
              @yield('breadcrumb', '')
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content p-3">
        @if (session('status'))
        <div class="alert alert-{{ session('status')[0] }} alert-dismissible fade show" role="alert">
          {{ session('status')[1] }}
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        </div>
        @endif
        @yield('content')
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
      <strong>Copyright &copy; 2021 </strong> All rights reserved.
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="{{ asset('js/jquery-3.6.0.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <!-- overlayScrollbars -->
  <script src="{{ asset('js/jquery.overlayScrollbars.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('js/adminlte.min.js') }}"></script>
  <!-- Additional Scripts -->
  @yield('scripts')
</body>

</html>
