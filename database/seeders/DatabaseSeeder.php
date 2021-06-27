<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->call([
      CountrySeeder::class,
      UserSeeder::class,
      WebsiteSeeder::class,
      BannerSeeder::class,
      SquareBannerSeeder::class,
      TextAdSeeder::class,
      UserTypeSeeder::class,
      SurferRewardSeeder::class
    ]);
  }
}
