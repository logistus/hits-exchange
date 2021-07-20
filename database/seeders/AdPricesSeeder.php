<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdPricesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('ad_prices')->insert([
      'ad_type' => 'Credits',
      'ad_amount' => 500,
      'price' => 3
    ]);

    DB::table('ad_prices')->insert([
      'ad_type' => 'Credits',
      'ad_amount' => 1000,
      'price' => 5
    ]);

    DB::table('ad_prices')->insert([
      'ad_type' => 'Banner Impressions',
      'ad_amount' => 50000,
      'price' => 20
    ]);

    DB::table('ad_prices')->insert([
      'ad_type' => 'Banner Impressions',
      'ad_amount' => 100000,
      'price' => 35
    ]);

    DB::table('ad_prices')->insert([
      'ad_type' => 'Square Banner Impressions',
      'ad_amount' => 50000,
      'price' => 20
    ]);

    DB::table('ad_prices')->insert([
      'ad_type' => 'Square Banner Impressions',
      'ad_amount' => 100000,
      'price' => 35
    ]);

    DB::table('ad_prices')->insert([
      'ad_type' => 'Text Ad Impressions',
      'ad_amount' => 50000,
      'price' => 5
    ]);

    DB::table('ad_prices')->insert([
      'ad_type' => 'Text Ad Impressions',
      'ad_amount' => 100000,
      'price' => 8
    ]);
  }
}
