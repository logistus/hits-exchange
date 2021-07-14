<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/referrals') }}">Referrals</a></h4>
  <x-alert />
  {{-- <pre>{{ var_dump($referrals) }}</pre> --}}
  @if ($referrals)
  <form action="{{ url('user/referrals') }}" method="GET" class="my-3 row row-cols-lg-auto align-items-center">
    <div class="col-12">
      <label for="sort_by">Sort by:</label>
    </div>
    <div class="col-12">
      <select class='form-select' id="sort_by" name="sort_by" aria-label='Sort Referrals'>
        <option value="join_date" {{ request()->get('sort_by') == '' || request()->get('sort_by') == 'join_date' ? 'selected' : '' }}>Join Date</option>
        <option value="last_login" {{ request()->get('sort_by') == 'last_login' ? 'selected' : '' }}>Last Login</option>
        <option value="user_type" {{ request()->get('sort_by') == 'user_type' ? 'selected' : '' }}>User Type</option>
        <option value="pages_surfed" {{ request()->get('sort_by') == 'pages_surfed' ? 'selected' : '' }}>Pages Surfed</option>
        <option value="total_purchased" {{ request()->get('sort_by') == 'total_purchased' ? 'selected' : '' }}>Total Purchased</option>
      </select>
    </div>
    <div class="col-12">
      <button type="submit" class="btn btn-primary">Sort</button>
    </div>
  </form>
  <table class="table table-bordered align-middle">
    <thead>
      <tr class="bg-light">
        <th scope="col"></th>
        <th scope="col">Username</th>
        <th scope="col">User Type</th>
        <th scope="col">Pages Surfed</th>
        <th scope="col">Total Purchased</th>
        <th scope="col">Status</th>
        <th scope="col">Joined From</th>
        <th scope="col">Last Login</th>
        <th scope="col">Join Date</th>
        <th scope="col"></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($referrals as $referral)
      <tr>
        <td><img src="{{ $referral->generate_gravatar($referral->id) }}" alt="{{ $referral->username }}" height="48" /></td>
        <td>{{ $referral->username }}</td>
        <td>{{ $referral->type->name }}</td>
        <td>{{ $referral->totalSurfed }}</td>
        <td>${{ $referral->orders->where('status', 'Completed')->sum('price') }}</td>
        <td>{{ $referral->status }}</td>
        <td>hungryforhits.com</td>
        <td>{{ $referral->last_login ? $referral->last_login : "Never" }}</td>
        <td>{{ $referral->join_date }}</td>
        <td class="d-flex flex-column">
          <a class="btn btn-secondary mb-3" href="{{ url('user/transfer_credits', $referral->id) }}">Transfer Credits</a>
          <a class="btn btn-secondary" href="{{ url('private_messages/compose_directly', $referral->id) }}">Send Message</a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $referrals->links() }}
  @else
  <div class="alert alert-info">You don't have any referrals. <a href="{{ url('promote') }}">Promote</a> {{ config('app.name') }} to get more referrals.</div>
  @endif
</x-layout>
