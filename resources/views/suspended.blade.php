<x-layout title="{{ $page }}">
  <div class="d-flex flex-column justify-content-center align-items-center h-100">
    <span style="font-size: 3rem;">🚨</span>
    <p>Your account has been suspended
      @if (Auth::user()->suspend_until)
      until {{ Auth::user()->suspend_until }}
      @endif
      .</p>
    @if (Auth::user()->suspend_reason)
    <p><strong>Reason: </strong> {{ Auth::user()->suspend_reason }}</p>
    @endif

    <p>Click <a href="{{ url('support') }}">here</a> to open a ticket if you think this is a mistake.</p>
  </div>
</x-layout>
