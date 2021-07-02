@extends('admin.layout')

@section('page')
Add Member
@endsection

@section('content')
<div class="container">
  <form action="{{ url('admin/members/add') }}" method="POST">
    @csrf
    <div class="form-row mb-3">
      <label for="join_date" class="col-sm-2 col-form-label">Join Date</label>
      <div class="col-sm-5">
        <input type="date" name="join_date" value="{{ date('Y-m-d') }}" class="form-control @error('join_date') border border-danger @enderror">
        @error('join_date')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="name" class="col-sm-2 col-form-label">Name</label>
      <div class="col-sm-5">
        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') border border-danger @enderror">
        @error('name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="surname" class="col-sm-2 col-form-label">Surname</label>
      <div class="col-sm-5">
        <input type="text" name="surname" value="{{ old('surname') }}" class="form-control @error('surname') border border-danger @enderror">
        @error('surname')
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
      <label for="email" class="col-sm-2 col-form-label">Email Address</label>
      <div class="col-sm-5">
        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') border border-danger @enderror">
        @error('email')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="country" class="col-sm-2 col-form-label">Country</label>
      <div class="col-sm-5">
        <select name="country" id="country" class="custom-select">
          <option value="">Select</option>
          @foreach ($countries as $country)
          <option value="{{ $country->code }}" {{ old('country') == $country->code ? 'selected' : ''}}>{{ $country->name }}</option>
          @endforeach
        </select>
        @error('country')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="password" class="col-sm-2 col-form-label">Password</label>
      <div class="col-sm-5">
        <input type="password" name="password" class="form-control @error('password') border border-danger @enderror">
        @error('password')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="upline" class="col-sm-2 col-form-label">Upline</label>
      <div class="col-sm-5">
        <input type="text" name="upline" class="form-control @error('upline') border border-danger @enderror">
        @error('upline')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="status" class="col-sm-2 col-form-label">Status</label>
      <div class="col-sm-5">
        <select name="status" id="status" class="custom-select">
          <option value="Unverified" {{ old('status') == 'Unverified' ? 'selected' : ''}}>Unverified</option>
          <option value="Active" {{ old('status') == 'Active' ? 'selected' : ''}}>Active</option>
          <option value="Suspended" {{ old('status') == 'Suspended' ? 'selected' : ''}}>Suspended</option>
        </select>
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="user_type" class="col-sm-2 col-form-label">Type</label>
      <div class="col-sm-5">
        <select name="user_type" id="user_type" class="custom-select">
          @foreach($user_types as $user_type)
          <option value="{{ $user_type->id }}">{{ $user_type->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Add Member</button>
  </form>
</div>
@endsection