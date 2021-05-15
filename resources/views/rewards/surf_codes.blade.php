<x-layout title="{{ $page }}">
  <h4><a href="{{ url('surf_codes') }}">Surf Codes</a></h4>
  <p>In the box below, type your surf code. To win your prize, simply follow the instructions.</p>
  <x-alert />
  <form action="{{ url('surf_codes') }}" method="POST" class="d-flex mb-3">
    @csrf
    <input type="text" name="surf_code" class="form-control" style="width: 20rem;" required>
    <button type="submit" class="btn btn-primary ms-3">Add</button>
  </form>
  <h4>Active Surf Codes</h4>
  @forelse ($active_surf_codes as $active_surf_code)
  <p>{{ $active_surf_code->code_info->code }} -
    @foreach ($active_surf_code->code_info->prizes as $prize)
    {{ $prize->prize_amount }} {{ $prize->prize_type }}
    @if (!$loop->last)
    and
    @endif
    @endforeach
    - Surf {{ ($active_surf_code->code_info->surf_amount - Auth::user()->surfed_today) > 0 ? 
    $active_surf_code->code_info->surf_amount - Auth::user()->surfed_today : 
    'couple of ' }} more pages today.
  </p>
  @empty
  <p>You don't have any active surf code.</p>
  @endforelse
  <h4>Completed Surf Codes</h4>
  @forelse ($completed_surf_codes as $completed_surf_code)
  <p>{{ $completed_surf_code->code_info->code }} -
    @foreach ($completed_surf_code->code_info->prizes as $prize)
    {{ $prize->prize_amount }} {{ $prize->prize_type }}
    @if (!$loop->last)
    and
    @endif
    @endforeach
    - Surf {{ $completed_surf_code->code_info->surf_amount }} pages.
  </p>
  @empty
  <p>You don't have any completed surf code.</p>
  @endforelse
</x-layout>