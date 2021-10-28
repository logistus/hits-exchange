@php
use \App\Models\SurfCode;
@endphp
@extends('admin.layout')

@section('page')
Surf Codes
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
  <li class="breadcrumb-item active">Surf Codes</li>
</ol>
@endsection

@section('content')
<p class="text-right">
  <a href="{{ url('admin/surf_codes/create') }}" class="btn btn-success" title="Add New Code"><i class="fas fa-plus"></i> Add New Code</a>
</p>
@if (count($surf_codes))
<table class="table table-bordered table-hover table-head-fixed">
  <thead>
    <tr>
      <th scope="col">Code</th>
      <th scope="col">Valid From</th>
      <th scope="col">Valid To</th>
      <th scope="col">Surf Amount</th>
      <th scope="col">Prizes</th>
      <th scope="col">Claimed By</th>
      <th scope="col">Active</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody class="bg-light">
    @foreach($surf_codes as $surf_code)
    <tr>
      <td>{{ $surf_code->code }}</td>
      <td>{{ $surf_code->valid_from }}</td>
      <td>{{ $surf_code->valid_to }}</td>
      <td>{{ $surf_code->surf_amount }}</td>
      <td>
        @forelse($surf_code->prizes as $prize)
        <div>
          {{ $prize->prize_type == "Purchase Balance" ? "$" : "" }}
          {{ $prize->prize_type == "Purchase Balance" ? $prize->prize_amount : number_format($prize->prize_amount) }}
          {{ $prize->prize_type }}
          <a class="btn btn-sm btn-danger mx-2" href="{{ url('admin/surf_codes/prizes/delete', $prize->id) }}" title="Delete Surf Code Prize">Delete</a>
        </div>
        @empty
        <div>No prize found</div>
        @endforelse
        <div class="my-3">
          <button type="button" data-toggle="modal" data-target="#prizeModal" data-codeid="{{ $surf_code->id }}" class="btn btn-sm btn-secondary" title="Manage Prizes">Add Prize</button>
        </div>
      </td>
      <td>{{ count($surf_code->completed_total) }} member{{ count($surf_code->completed_total) > 1 ? "s" : "" }}</td>
      <td>{{ $surf_code->confirmed ? "Yes" : "No" }}</td>
      <td>
        <div class="btn-group" role="group" aria-label="Manage Surf Codes">
          <a class="btn btn-sm btn-primary" href="{{ url('admin/surf_codes/edit', $surf_code->id) }}" title="Edit Surf Code"><i class="fas fa-edit"></i></a>
          <a class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');" href="{{ url('admin/surf_codes/delete', $surf_code->id) }}" title="Delete Surf Code"><i class="fas fa-trash"></i></a>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@else
<p>No surf code found.</p>
@endif
<!-- Prize Modal -->
<div class="modal fade" id="prizeModal" tabindex="-1" aria-labelledby="prizeModal" aria-hidden="true">
  <form action="{{ url('admin/surf_codes/prizes') }}" method="POST">
    @csrf
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="prizeModal">Add Prize</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="prize_amount">Prize Amount</label>
            <input type="text" class="form-control" id="prize_amount" name="prize_amount" aria-describedby="Prize Amount">
          </div>
          <div class="form-group">
            <label for="prize_type">Prize Type</label>
            <select class="form-control" id="prize_type" name="prize_type">
              <option value="Credits">Credits</option>
              <option value="Banner Impressions">Banner Impressions</option>
              <option value="Square Banner Impressions">Square Banner Impressions</option>
              <option value="Text Ad Impressions">Text Ad Impressions</option>
              <option value="Purchase Balance">Purchase Balance</option>
            </select>
            <input type="hidden" name="code_id" id="code_id">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Prize</button>
        </div>
      </div>
    </div>
  </form>
</div>
<!-- End Prize Modal -->
@endsection

@section('scripts')
<script>
  $(function() {
    $('#prizeModal').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget); // Button that triggered the modal
      var code_id = button.data('codeid'); // Extract info from data-* attributes
      var modal = $(this);
      modal.find('.modal-body #code_id').val(code_id);
    });

  });

</script>
@endsection
