@if (session('status'))
<div class="alert alert-{{ session('status')[0] }} alert-dismissible mt-3 fade show" role="alert">
  {{ session('status')[1] }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif