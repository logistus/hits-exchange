@php
use Carbon\Carbon;
$sort = (request()->get('sort') == 'desc' || request()->get('sort') == '') ? 'asc' : 'desc';
$sort_icon = (request()->get('sort') == 'desc' || request()->get('sort') == '') ? 'down' : 'up';
@endphp
<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/referrals') }}">Referrals</a></h4>
  <x-alert />
  @if ($referrals && count($referrals))
  <p class="mb-0 px-1 py-3"><strong>Viewing:</strong>
    {{ $referrals->firstItem() }} to {{ $referrals->lastItem() }} of {{ count(Auth::user()->referrals) }} total referrals
  </p>
  <div class="p-3 ps-0 d-flex align-items-center">
    <span class="me-3">Filter by: </span>
    <form action="{{ url('user/referrals') }}" method="GET" class="d-flex align-items-center">
      <select class="form-select me-3" name="filterByUserType" id="filterByUserType">
        <option value="" selected>Select User Type</option>
        @foreach ($user_types as $user_type)
        <option value="{{ $user_type->id }}" {{ request()->get('filterByUserType') == $user_type->id ? "selected" : "" }}>{{ $user_type->name }}</option>
        @endforeach
      </select>
      <select class="form-select me-3" id="filterByStatus" name="filterByStatus">
        <option value="" selected>Select Status</option>
        <option value="Unverified" {{ request()->get('filterByStatus') == "Unverified" ? "selected" : "" }}>Unverified</option>
        <option value="Active" {{ request()->get('filterByStatus') == "Active" ? "selected" : "" }}>Active</option>
        <option value="Suspended" {{ request()->get('filterByStatus') == "Suspended" ? "selected" : "" }}>Suspended</option>
      </select>
      <input type="text" name="filterByTracker" id="filterByTracker" class="form-control me-3" placeholder="tracker name" value="{{ request()->get('filterByTracker') }}">
      <input type="text" name="filterByUsername" id="filterByUsername" class="form-control me-3" placeholder="username" value="{{ request()->get('filterByUsername') }}">
      <button class="btn btn-primary me-2" id="applyFilters" type="submit">Apply</button>
      <a href="{{ url('user/referrals') }}" class="btn btn-secondary">Reset</a>
    </form>
  </div>
  @if (count($referrals))
  <table class="table align-middle mt-3">
    <thead>
      <tr class="bg-light">
        <th scope="col"></th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'username', 'sort' => $sort]) }}">Username</a>
          @if (request()->get('sort_by') == "username")
          <i class="bi bi-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'user_type', 'sort' => $sort]) }}">User Type</a>
          @if (request()->get('sort_by') == "user_type")
          <i class="bi bi-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'pages_surfed', 'sort' => $sort]) }}">Pages Surfed</a>
          @if (request()->get('sort_by') == "pages_surfed")
          <i class="bi bi-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'total_purchased', 'sort' => $sort]) }}">Total Purchased</a>
          @if (request()->get('sort_by') == "total_purchased")
          <i class="bi bi-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort' => $sort]) }}">Status</a>
          @if (request()->get('sort_by') == "status")
          <i class="bi bi-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'tracker', 'sort' => $sort]) }}">Tracker</a>
          @if (request()->get('sort_by') == "tracker")
          <i class="bi bi-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'last_login', 'sort' => $sort]) }}">Last Login</a>
          @if (request()->get('sort_by') == "last_login")
          <i class="bi bi-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'join_date', 'sort' => $sort]) }}">Join Date</a>
          @if (request()->get('sort_by') == "join_date" || request()->get('sort_by') == "")
          <i class="bi bi-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($referrals as $referral)
      <tr>
        <td><img src="{{ $referral->generate_gravatar($referral->id) }}" alt="{{ $referral->username }}" height="40" /></td>
        <td>{{ $referral->username }}</td>
        <td>{{ $referral->type->name }}</td>
        <td>{{ $referral->totalSurfed }}</td>
        <td>${{ $referral->total_purchased }}</td>
        <td>{{ $referral->status }}</td>
        <td>{{ $referral->tracker }}</td>
        <td>{{ $referral->last_login ? Carbon::create($referral->last_login)->format("j F Y") : "Never" }}</td>
        <td>{{ Carbon::create($referral->join_date)->format("j F Y") }}</td>
        <td class="d-flex">
          <div data-bs-toggle="tooltip" data-bs-placement="top" title="Transfer Credits">
            <button class="btn btn-outline-secondary me-3" data-bs-toggle="modal" data-bs-target="#transferCreditsModal" data-bs-referral="{{ $referral->username }}" data-bs-id="{{ $referral->id }}"><i class="bi bi-arrow-left-right"></i></button>
          </div>
          <a class="btn btn-outline-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Send PM" href="{{ url('private_messages/compose_directly', $referral->id) }}"><i class="bi bi-envelope"></i></a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $referrals->links() }}
  @else
  <p>No results with the given filters</p>
  @endif
  @else
  <div class="alert alert-info">You don't have any referrals. <a href="{{ url('promote') }}">Promote</a> {{ config('app.name') }} to get more referrals.</div>
  @endif
  <!-- Transfer credits modal -->
  <div class="modal fade" id="transferCreditsModal" tabindex="-1" aria-labelledby="transferCredits" aria-hidden="true">
    <form action="{{ url('user/transfer_credits') }}" id="transfer_form" method="POST">
      @csrf
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addNewWebsite">Transfer Credits</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>You are transferring credits to your referral <strong><span id="transfer_to"></span></strong></p>
            <div class="mb-3">
              <label for="credits" class="form-label">Credits Amount (You ve {{ Auth::user()->credits }} credits)</label>
              <input type="number" class="form-control" name="credits" id="credits" min="0" max="{{ Auth::user()->credits }}" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="action" value="transfer_credits">Transfer</button>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- End of transfer credits modal -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script>
    $(function() {
      $("#transferCreditsModal").on('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var referral = button.getAttribute("data-bs-referral");
        var referral_id = button.getAttribute("data-bs-id");
        $("#transfer_to").text(referral);
        $("#transfer_form").attr("action", "/user/transfer_credits/" + referral_id);
      });
    });

  </script>
</x-layout>
