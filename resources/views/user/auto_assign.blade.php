<x-layout title="{{ $page }}">
  <h4><a href="{{ url('websites/auto_assign') }}">Auto Assign</a></h4>
  <div class="alert alert-warning">
    <div>You must auto assign <strong>{{ Auth::user()->type->min_auto_assign }}%</strong> of your credits.</div>
    <div><strong>{{ Auth::user()->websites->sum('auto_assign') }}%</strong> of your credits are currently auto assigned.</div>
  </div>
  @if (count($websites))
  @error('aa_values')
  <div class="alert alert-danger">Auto assign values must be numeric values.</div>
  @enderror
  <form action="{{ url('websites/auto_assign') }}" method="POST">
    @csrf
    <x-alert />
    <table class="table table-bordered align-middle">
      <tr class="bg-light">
        <th scope="col">Website URL</th>
        <th scope="col">Auto Assign Percent</th>
      </tr>
      @foreach ($websites as $website)
      <tr>
        <td>
          <input type="text" class="form-control" value="{{ $website->url }}" readonly />
        </td>
        <td>
          <input type="number" class="form-control" name="aa_values[{{ $website->id }}]" id="aa-value" value="{{ $website->auto_assign }}" style="width: 7rem;">
        </td>
      </tr>
      @endforeach
      <tr>
        <td colspan="2" class="text-center bg-light">
          <button type="submit" class="btn btn-primary">Update</button>
        </td>
      </tr>
    </table>
  </form>
  @else
  <p class="alert alert-info">You don't have any websites.</p>
  @endif
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script>
    $(function() {

    });
  </script>
</x-layout>