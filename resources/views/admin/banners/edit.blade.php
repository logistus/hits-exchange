@php
use \App\Models\User;
@endphp

@extends('admin.layout')

@section('page')
Edit Banner
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ url('admin/banners/list') }}">Banners</a></li>
  <li class="breadcrumb-item active">Edit Banner</li>
</ol>
@endsection

@section('content')
<div class="container">
  <form action="{{ url()->current() }}" method="POST">
    @csrf
    <div class="form-row mb-3">
      <label for="target_url" class="col-sm-2 col-form-label">Target URL</label>
      <div class="col-sm-5">
        <input type="url" name="target_url" value="{{ $banner->target_url }}" class="form-control @error('target_url') border border-danger @enderror">
        @error('target_url')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="image_url" class="col-sm-2 col-form-label">Image URL</label>
      <div class="col-sm-5">
        <input type="url" name="image_url" value="{{ $banner->image_url }}" class="form-control @error('image_url') border border-danger @enderror">
        @error('image_url')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="username" class="col-sm-2 col-form-label">Username</label>
      <div class="col-sm-5">
        <input type="text" name="username" value="{{ User::where('id', $banner->user_id)->value('username') }}" class="form-control @error('username') border border-danger @enderror">
        @error('username')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="assigned" class="col-sm-2 col-form-label">Assigned</label>
      <div class="col-sm-5">
        <input type="text" name="assigned" value="{{ $banner->assigned }}" class="form-control @error('assigned') border border-danger @enderror">
        @error('assigned')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="status" class="col-sm-2 col-form-label">Status</label>
      <div class="col-sm-5">
        <select name="status" id="status" class="custom-select">
          <option value="Pending" {{ $banner->status == "Pending" ? "selected" : ""}}>Pending</option>
          <option value="Active" {{ $banner->status == "Active" ? "selected" : ""}}>Active</option>
          <option value="Paused" {{ $banner->status == "Paused" ? "selected" : ""}}>Paused</option>
          <option value="Suspended" {{ $banner->status == "Suspended" ? "selected" : ""}}>Suspended</option>
        </select>
      </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
    <a href="{{ url('admin/banners/list') }}" class="btn btn-secondary mt-3">Cancel</a>
  </form>
</div>
@endsection
