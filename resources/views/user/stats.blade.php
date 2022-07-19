@php
use Carbon\Carbon;
$yesterday = Carbon::yesterday()->format("Y-m-d");
@endphp
<x-layout title="{{ $page }}">
  <h4 class="my-3"><a href="{{ url('user/stats') }}">{{ Auth::user()->username }}'s Stats</a></h4>
  <div class="table-responsive">
    <table class="table align-middle">
      <tr class="text-bg-primary">
        <th colspan="4">Account Stats</th>
      </tr>
      <tr>
        <th class="text-bg-light">Member Since</th>
        <td>{{ Carbon::create(Auth::user()->join_date)->format("j F Y") }}</td>
        <th class="text-bg-light">Upline</th>
        <td>{{ Auth::user()->upline ? Auth::user()->upline : "N/A" }}</td>
      </tr>
      <tr>
        <th class="text-bg-light">Account Type</th>
        <td>{{ Auth::user()->type->name }}</td>
        <th class="text-bg-light">Upgrade Expires</th>
        <td>{{ Auth::user()->upgrade_expires ? date("F j, Y, g:i a", Auth::user()->upgrade_expires) : "Never" }}</td>
      </tr>
      <tr>
        <th class="text-bg-light">Surf Timer</th>
        <td>{{ Auth::user()->type->surf_timer }} seconds</td>
        <th class="text-bg-light">Surf Ratio</th>
        <td>{{ Auth::user()->type->surf_ratio }} credit per click</td>
      </tr>
      <tr>
        <th class="text-bg-light">Referrals</th>
        <td>{{ count(Auth::user()->referrals) }} (<a href="{{ url('user/referrals') }}">Details</a>)</td>
        <th class="text-bg-light">Purchase Balance</th>
        <td>${{ number_format(Auth::user()->purchase_balance_completed->sum('amount'), 2) }}</td>
      </tr>
      <tr>
        <th class="text-bg-light">Paid Commissions</th>
        <td>${{ number_format(Auth::user()->commissions_paid->sum('amount'), 2) }}</td>
        <th class="text-bg-light">Unpaid Commissions</th>
        <td>${{ number_format(Auth::user()->commissions_all->sum('amount'), 2) }}</td>
      </tr>
      <tr class="text-bg-primary">
        <th colspan="4">Advertisement Stats</th>
      </tr>
      <tr class="text-bg-light">
        <th>Advert Type</th>
        <th>Advert In Rotation</th>
        <th>Assigned Credits</th>
        <th>Unassigned Credits</th>
      </tr>
      <tr>
        <th class="text-bg-light">Website</td>
        <td>{{ count(Auth::user()->websites) }}</td>
        <td>{{ number_format(Auth::user()->websites()->sum('assigned')) }}</td>
        <td>{{ number_format(Auth::user()->credits) }} (<a href="{{ url('websites') }}">Assign</a>)</td>
      </tr>
      <tr>
        <th class="text-bg-light">Banner</td>
        <td>{{ count(Auth::user()->banners) }}</td>
        <td>{{ number_format(Auth::user()->banners()->sum('assigned')) }}</td>
        <td>{{ number_format(Auth::user()->banner_imps) }} (<a href="{{ url('banners') }}">Assign</a>)</td>
      </tr>
      <tr>
        <th class="text-bg-light">Square Banner</td>
        <td>{{ count(Auth::user()->square_banners) }}</td>
        <td>{{ number_format(Auth::user()->square_banners()->sum('assigned')) }}</td>
        <td>{{ number_format(Auth::user()->square_banner_imps) }} (<a href="{{ url('square_banners') }}">Assign</a>)</td>
        <td></td>
      </tr>
      <tr>
        <th class="text-bg-light">Text Ad</td>
        <td>{{ count(Auth::user()->texts) }}</td>
        <td>{{ number_format(Auth::user()->texts()->sum('assigned')) }}</td>
        <td>{{ number_format(Auth::user()->text_imps) }} (<a href="{{ url('texts') }}">Assign</a>)</td>
      </tr>
      <tr class="text-bg-primary">
        <th colspan="4">Surfing Stats</th>
      </tr>
      <tr>
        <th class="text-bg-light">Lifetime Surfed</th>
        <td>{{ number_format(Auth::user()->surf_histories()->sum('surfed_total')) }}</td>
        <th class="text-bg-light">Lifetime Credits Earned</th>
        <td>{{ number_format(Auth::user()->surf_histories()->sum('credits_total'), 2) }}</td>
      </tr>
      <tr>
        <th class="text-bg-light">Surfed Today</th>
        <td>{{
          number_format(Auth::user()->surf_histories()->where('surf_date', date('Y-m-d'))->value('surfed_total'))
          }}</td>
        <th class="text-bg-light">Surfed Yesterday</th>
        <td>{{
          number_format(Auth::user()->surf_histories()->where('surf_date', $yesterday)->value('surfed_total'))
         }}</td>
      </tr>
      <tr>
        <th class="text-bg-light">Maximum Surf in 1 day</th>
        <td>
          @if (Auth::user()->surf_histories()->max('surfed_total'))
          {{
          Auth::user()->surf_histories()->max('surfed_total')
         }} pages surfed on
          {{ Carbon::create(Auth::user()->surf_histories()->where('surfed_total', Auth::user()->surf_histories()->max('surfed_total'))
         ->value('surf_date'))->format("j F Y")
         }}
          @else
          N/A
          @endif
        </td>
      </tr>
      <tr class="text-bg-primary">
        <th colspan="4">Prizes Won</th>
      </tr>
      <tr>
        <th class="text-bg-light">Website Credits Won</th>
        <td>{{ number_format(Auth::user()->credit_prize_won) }}</td>
        <th class="text-bg-light">Banner Impressions Won</th>
        <td>{{ number_format(Auth::user()->banner_prize_won) }}</td>
      </tr>
      <tr>
        <th class="text-bg-light">Square Banner Imps Won</th>
        <td>{{ number_format(Auth::user()->square_banner_prize_won) }}</td>
        <th class="text-bg-light">Text Ad Impressions Won</th>
        <td>{{ number_format(Auth::user()->text_ad_prize_won) }}</td>
      </tr>
      <tr>
        <th class="text-bg-light">Purchase Balance Won</th>
        <td colspan="3">${{ number_format(Auth::user()->purchase_balance_won, 2) }}</td>
      </tr>
    </table>
  </div>
</x-layout>
