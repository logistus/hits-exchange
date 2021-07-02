@php
use \App\Models\User;
@endphp

@extends('admin.layout')

@section('page')
Dashboard
@endsection

@section('stylesheets')
<link rel="stylesheet" href="{{ asset('css/Chart.min.css') }}">
@endsection

@section('content')
<h4>Members Stats</h4>

<div class="row">
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
      <div class="info-box-content">
        <span class="info-box-text"><a href="{{ url('admin/members/list') }}">Total Members</a></span>
        <span class="info-box-number">{{ count(User::all()) }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-info"><i class="fas fa-user-lock"></i></span>
      <div class="info-box-content">
        <span class="info-box-text"><a href="{{ url('admin/members/list?filterByStatus=Unverified') }}">Unverified Members</a></span>
        <span class="info-box-number">{{ count(User::where('status', 'Unverified')->get()) }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-info"><i class="fas fa-user-check"></i></span>
      <div class="info-box-content">
        <span class="info-box-text"><a href="{{ url('admin/members/list?filterByStatus=Active') }}">Active Members</a></span>
        <span class="info-box-number">{{ count(User::where('status', 'Active')->get()) }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3 col-sm-6 col-12">
    <div class="info-box">
      <span class="info-box-icon bg-info"><i class="fas fa-user-alt-slash"></i></span>
      <div class="info-box-content">
        <span class="info-box-text"><a href="{{ url('admin/members/list?filterByStatus=Suspended') }}">Suspended Members</a></span>
        <span class="info-box-number">{{ count(User::where('status', 'Suspended')->get()) }}</span>
      </div>
    </div>
  </div>
</div>
<div class="row mt-3">
  <div class="col-md-6 col-12">
    <h4>New Members Last 7 Days</h4>
    <canvas id="newMembersDaily" style="max-height: 250px; max-width: 500px;"></canvas>
  </div>
  <div class="col-md-6 col-12">
    <h4>Total Surf Last 7 Days</h4>
    <canvas id="surfDaily" style="max-height: 250px; max-width: 500px;"></canvas>
  </div>
</div>
@section('scripts')
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script>
  var ctx = document.getElementById('newMembersDaily');
  var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['2021-06-23', '2021-06-24', '2021-06-25', '2021-06-26', '2021-06-27', '2021-06-28', '2021-06-29'],
      datasets: [{
        label: 'New Members',
        data: [2, 20, 15, 0, 19, 10, 3],
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
@endsection
@endsection