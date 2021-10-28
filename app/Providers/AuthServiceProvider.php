<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * The policy mappings for the application.
   *
   * @var array
   */
  protected $policies = [
    'App\Models\Website' => 'App\Policies\WebsitePolicy',
    'App\Models\Banner' => 'App\Policies\BannerPolicy',
    'App\Models\SquareBanner' => 'App\Policies\SquareBannerPolicy',
    'App\Models\TextAd' => 'App\Policies\TextAdPolicy',
    'App\Models\StartPage' => 'App\Policies\StartPagePolicy',
    'App\Models\LoginSpotlight' => 'App\Policies\LoginSpotlightPolicy',
    'App\Models\PrivateMessage' => 'App\Policies\PrivateMessagePolicy',
    'App\Models\Order' => 'App\Policies\OrderPolicy',
    'App\Models\PurchaseBalance' => 'App\Policies\PurchaseBalancePolicy',
  ];

  /**
   * Register any authentication / authorization services.
   *
   * @return void
   */
  public function boot()
  {
    $this->registerPolicies();

    //
  }
}
