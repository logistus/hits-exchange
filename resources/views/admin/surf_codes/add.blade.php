@extends('admin.layout')

@section('page')
Add Surf Code
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ url('admin/surf_codes') }}">Surf Codes</a></li>
  <li class="breadcrumb-item active">Add Surf Code</li>
</ol>
@endsection

@section('content')
<div class="container">
  <form action='{{ url("admin/surf_codes") }}' method='POST'>
    @csrf
    <div class="form-row mb-3">
      <label class="col-sm-2 col-form-label" for="code">Code</label>
      <div class="col-sm-5">
        <input type="text" class="form-control @error('code') border border-danger @enderror" value="{{ old('code') }}" id="code" name="code" aria-describedby="Surf Code">
        @error('code')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-2 col-form-label" for="valid_from">Valid From</label>
      <div class="col-sm-5">
        <input type="date" class="form-control @error('valid_from') border border-danger @enderror" value="{{ old('valid_from') }}" id="valid_from" name="valid_from" aria-describedby="Valid From">
        @error('valid_from')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-2 col-form-label" for="valid_to">Valid To</label>
      <div class="col-sm-5">
        <input type="date" class="form-control @error('valid_to') border border-danger @enderror" value="{{ old('valid_to') }}" id="valid_to" name="valid_to" aria-describedby="Valid To">
        @error('valid_to')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-2 col-form-label" for="surf_amount">Surf Amount</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('surf_amount') border border-danger @enderror" value="{{ old('surf_amount') }}" id="surf_amount" name="surf_amount" aria-describedby="Surf Amount">
        @error('surf_amount')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-2 col-form-label" for="confirmed">Confirmed</label>
      <div class="col-sm-5">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="1" id="confirmed" name="confirmed">
        </div>
      </div>
    </div>
    <a href="{{ url('admin/surf_codes') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Add New Code</button>
  </form>
</div>
@endsection
