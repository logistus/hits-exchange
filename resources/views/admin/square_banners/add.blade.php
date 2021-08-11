@extends('admin.layout')

@section('page')
Add Square Banner
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ url('admin/square_banners/list') }}">Square Banners</a></li>
  <li class="breadcrumb-item active">Add Square Banner</li>
</ol>
@endsection

@section('content')
<div class="container">
  <form action="{{ url('admin/square_banners/add') }}" method="POST">
    @csrf
    <div class="form-row mb-3">
      <label for="image_url" class="col-sm-2 col-form-label">Image URL</label>
      <div class="col-sm-5">
        <input type="url" name="image_url" value="{{ old('image_url') }}" class="form-control @error('image_url') border border-danger @enderror">
        @error('image_url')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="target_url" class="col-sm-2 col-form-label">Target URL</label>
      <div class="col-sm-5">
        <input type="url" name="target_url" value="{{ old('target_url') }}" class="form-control @error('target_url') border border-danger @enderror">
        @error('target_url')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="username" class="col-sm-2 col-form-label">Username</label>
      <div class="col-sm-5">
        <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') border border-danger @enderror">
        @error('username')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="assigned" class="col-sm-2 col-form-label">Assigned</label>
      <div class="col-sm-5">
        <input type="text" name="assigned" value="{{ old('assigned') ?  old('assigned') : '0' }}" class="form-control @error('assigned') border border-danger @enderror">
        @error('assigned')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="status" class="col-sm-2 col-form-label">Status</label>
      <div class="col-sm-5">
        <select name="status" id="status" class="custom-select">
          <option value="Pending">Pending</option>
          <option value="Active" selected>Active</option>
          <option value="Paused">Paused</option>
          <option value="Suspended">Suspended</option>
        </select>
      </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Add Square Banner</button>
  </form>
</div>
@endsection
