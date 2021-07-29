@php
use Carbon\Carbon;
@endphp
<x-layout title="{{ $page }}">
  <h4><a href="{{ url('upgrade') }}">Upgrade</a></h4>
  <x-alert />
  <p>You are {{ (Auth::user()->type->name != "Free" && Auth::user()->upgrade_expires == NULL) ? "Lifetime " : "" }} {{ Auth::user()->type->name }} Member
    {{ (Auth::user()->type->name != "Free" && Auth::user()->upgrade_expires != NULL) ?  " and it expires at ".
    
    Carbon::createFromTimeStamp(Auth::user()->upgrade_expires)->toDayDateTimeString()
     : "" }}
  </p>
  <table class="table table-bordered align-middle">
    <tr class="bg-light">
      <th scope="col"></th>
      @foreach ($user_types as $user_type)
      <th scope="col">{{ $user_type->name }}</th>
      @endforeach
    </tr>
    <tbody>
      <tr>
        <td>Surf Timer</td>
        @foreach ($user_types as $user_type)
        <td>{{ $user_type->surf_timer }} seconds</td>
        @endforeach
      </tr>
      <tr>
        <td>Credits per click</td>
        @foreach ($user_types as $user_type)
        <td>{{ $user_type->surf_ratio }}</td>
        @endforeach
      </tr>
      <tr>
        <td>Commission</td>
        @foreach ($user_types as $user_type)
        <td>{{ $user_type->commission_ratio }}%</td>
        @endforeach
      </tr>
      <tr>
        <td>Credits from referrals</td>
        @foreach ($user_types as $user_type)
        <td>{{ $user_type->credit_reward_ratio }}%</td>
        @endforeach
      </tr>
      <tr>
        <td>Minimum Auto Assign</td>
        @foreach ($user_types as $user_type)
        <td>{{ $user_type->min_auto_assign }}%</td>
        @endforeach
      </tr>
      <tr>
        <td>Customize Text Ads</td>
        @foreach ($user_types as $user_type)
        <td>{{ $user_type->customize_text_ads ? "Yes" : "No" }}</td>
        @endforeach
      </tr>
      <tr>
        <td>Maximum Websites</td>
        @foreach ($user_types as $user_type)
        <td>{{ $user_type->max_websites }}</td>
        @endforeach
      </tr>
      <tr>
        <td>Maximum Banners</td>
        @foreach ($user_types as $user_type)
        <td>{{ $user_type->max_banners }}</td>
        @endforeach
      </tr>
      <tr>
        <td>Maximum Square Banners</td>
        @foreach ($user_types as $user_type)
        <td>{{ $user_type->max_square_banners }}</td>
        @endforeach
      </tr>
      <tr>
        <td>Maximum Text Ads</td>
        @foreach ($user_types as $user_type)
        <td>{{ $user_type->max_texts }}</td>
        @endforeach
      </tr>
      <tr>
        <td>Credit to Banner Imps Conversion</td>
        @foreach ($user_types as $user_type)
        <td>{{ $user_type->credits_to_banner }}</td>
        @endforeach
      </tr>
      <tr>
        <td>Credit to Square Banner Imps Conversion</td>
        @foreach ($user_types as $user_type)
        <td>{{ $user_type->credits_to_square_banner }}</td>
        @endforeach
      </tr>
      <tr>
        <td>Credit to Text Ad Imps Conversion</td>
        @foreach ($user_types as $user_type)
        <td>{{ $user_type->credits_to_text }}</td>
        @endforeach
      </tr>
      <tr>
        <td>Price</td>
        @foreach ($user_types as $user_type)
        @if (count($user_type->prices) > 0)
        <td>
          @foreach ($user_type->prices as $price)
          <form method="POST" action="{{ url('buy/upgrade/'.$user_type->id.'/'.$price->id) }}" class="mb-3">
            @csrf
            {{ $price->time_amount . " " . $price->time_type }}{{ $price->time_amount > 1 ? "s" : "" }} {{ " - $" . $price->price }}
            <button type="submit" class="btn btn-sm btn-warning">Place Order</button>
          </form>
          @endforeach
        </td>
        @else
        <td>Free</td>
        @endif
        @endforeach
      </tr>
    </tbody>
  </table>
</x-layout>
