@php
use \App\Models\UserType;

$sort = (request()->get('sort') == 'desc' || request()->get('sort') == '') ? 'asc' : 'desc';
$sort_icon = (request()->get('sort') == 'desc' || request()->get('sort') == '') ? 'down' : 'up';
@endphp

@extends('admin.layout')

@section('page')
List Member Types
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
  <li class="breadcrumb-item active">Member Types</li>
</ol>
@endsection

@section('content')
<p class="text-right">
  <a href="{{ url('admin/member_types/create') }}" class="btn btn-success" title="Add New Type"><i class="fas fa-plus"></i> Add New Type</a>
</p>
@if (count($user_types))
<table class="table table-bordered table-hover table-head-fixed">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Surf Timer</th>
      <th scope="col">Surf Ratio</th>
      <th scope="col">Commission Ratio</th>
      <th scope="col">Max. Websites</th>
      <th scope="col">Max. Banners</th>
      <th scope="col">Max. Square Banners</th>
      <th scope="col">Max. Text Ads</th>
      <th scope="col">Customize Text Ads</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody class="bg-light">
    @foreach ($user_types as $user_type)
    <tr>
      <td>{{ $user_type->name }}</td>
      <td>{{ $user_type->surf_timer }} seconds</td>
      <td>{{ $user_type->surf_ratio }}</td>
      <td>{{ $user_type->commission_ratio }}%</td>
      <td>{{ $user_type->max_websites }}</td>
      <td>{{ $user_type->max_banners }}</td>
      <td>{{ $user_type->max_square_banners }}</td>
      <td>{{ $user_type->max_texts }}</td>
      <td>{{ $user_type->customize_text_ads == 1 ? "Yes" : "No" }}</td>
      <td>
        <div class="btn-group" role="group" aria-label="Manage Member Types">
          <a class="btn btn-sm btn-primary" href="{{ url('admin/member_types/edit', $user_type->id) }}" title="Edit Member Type"><i class="fas fa-edit"></i></a>
          <a class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');" href="{{ url('admin/member_types/delete', $user_type->id) }}" title="Delete Member Type"><i class="fas fa-trash"></i></a>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@else
<p>No member types found.</p>
@endif
<!-- Add New Type Modal -->
<div class="modal fade" id="addTypeModal" tabindex="-1" aria-labelledby="addTypeModal" aria-hidden="true">
  <form action="{{ url('admin/member_types') }}" method="POST">
    @csrf
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addTypeModal">Add New Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" aria-describedby="Type Name">
          </div>
          <div class="form-group">
            <label for="surf_timer">Surf Timer <small>(as seconds)</small></label>
            <input type="number" class="form-control" id="surf_timer" name="surf_timer" aria-describedby="Surf Timer">
          </div>
          <div class="form-group">
            <label for="surf_ratio">Surf Ratio</label>
            <input type="number" class="form-control" id="surf_ratio" name="surf_ratio" aria-describedby="Surf Ratio">
          </div>
          <div class="form-group">
            <label for="commission_ratio">Commission Ratio <small>(as percent)</small></label>
            <input type="number" class="form-control" id="commission_ratio" name="commission_ratio" aria-describedby="Commission Ratio">
          </div>
          <div class="form-group">
            <label for="max_websites">Max Websites</label>
            <input type="number" class="form-control" id="max_websites" name="max_websites" aria-describedby="Max Websites">
          </div>
          <div class="form-group">
            <label for="max_banners">Max Banners</label>
            <input type="number" class="form-control" id="max_banners" name="max_banners" aria-describedby="Max Banners">
          </div>
          <div class="form-group">
            <label for="max_square_banners">Max Square Banners</label>
            <input type="number" class="form-control" id="max_square_banners" name="max_square_banners" aria-describedby="Max Square Banners">
          </div>
          <div class="form-group">
            <label for="max_texts">Max Text Ads</label>
            <input type="number" class="form-control" id="max_texts" name="max_texts" aria-describedby="Max Text Ads">
          </div>
          <div class="form-group">
            <label for="min_auto_assign">Min Auto Assign</label>
            <input type="number" class="form-control" id="min_auto_assign" name="min_auto_assign" aria-describedby="Min Auto Assign">
          </div>
          <div class="form-group">
            <label for="credit_reward_ratio">Credits from referrals</label>
            <input type="number" class="form-control" id="credit_reward_ratio" name="credit_reward_ratio" aria-describedby="Credits from referrals">
          </div>
          <div class="form-group">
            <label for="credits_to_banner">Credits to banners</label>
            <input type="number" class="form-control" id="credits_to_banner" name="credits_to_banner" aria-describedby="Credits to banners">
          </div>
          <div class="form-group">
            <label for="credits_to_square_banner">Credits to square banners</label>
            <input type="number" class="form-control" id="credits_to_square_banner" name="credits_to_square_banner" aria-describedby="Credits to square banners">
          </div>
          <div class="form-group">
            <label for="credits_to_text">Credits to text ads</label>
            <input type="number" class="form-control" id="credits_to_text" name="credits_to_text" aria-describedby="Credits to text ads">
          </div>
          <div class="form-group form-check">
            <input class="form-check-input" type="checkbox" value="1" name="customize_text_ads" id="customize_text_ads">
            <label for="customize_text_ads">Can customize text ads</label>
          </div>
          <div class="form-group default_text_ad_color">
            <label for="default_text_ad_color">Default text ad color</label>
            <input class="form-control" type="color" value="#ffffff" name="default_text_ad_color" id="default_text_ad_color">
          </div>
          <div class="form-group default_text_ad_bg_color">
            <label for="default_text_ad_bg_color">Default text ad background color</label>
            <input class="form-control" type="color" value="#1246e2" name="default_text_ad_bg_color" id="default_text_ad_bg_color">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add New Type</button>
        </div>
      </div>
    </div>
  </form>
</div>
<!-- End Add New Type Modal -->
@endsection
