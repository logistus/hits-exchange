@php
$sort = (request()->get('sort') == 'desc' || request()->get('sort') == '') ? 'asc' : 'desc';
$sort_icon = (request()->get('sort') == 'desc' || request()->get('sort') == '') ? 'down' : 'up';
@endphp
<x-layout title="{{ $page }}">
  <h4><a href="{{ url('promote/trackers') }}">Promo Trackers</a></h4>
  <x-alert />
  @if (count($promo_trackers))
  <table class="table align-middle mt-3">
    <thead>
      <tr class="text-bg-light">
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'tracker_name', 'sort' => $sort]) }}">Tracker Name</a>
          @if (request()->get('sort_by') == "tracker_name")
          <i class="bi bi-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'total_hits', 'sort' => $sort]) }}">Total Hits</a>
          @if (request()->get('sort_by') == "total_hits" || request()->get('sort_by') == '')
          <i class="bi bi-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'referrals', 'sort' => $sort]) }}">Referrals</a>
          @if (request()->get('sort_by') == "referrals")
          <i class="bi bi-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
      </tr>
    </thead>
    <tbody>
      @foreach ($promo_trackers as $promo_tracker)
      <tr>
        <td>{{ $promo_tracker->tracker_name }}</td>
        <td>{{ $promo_tracker->total_hits }}</td>
        <td>{{
          request()->get('sort_by') == "referrals" ? 
          $promo_tracker->referrals : 
          count($promo_tracker->referrals->where('upline', Auth::id())) 
        }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $promo_trackers->links() }}
  @else
  <p>You don't have any trackers.</p>
  @endif
</x-layout>
