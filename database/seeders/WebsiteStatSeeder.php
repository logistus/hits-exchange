<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteStatSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('website_stats')->insert(
      [
        'website_id' => 1,
        'view_date' => '2022-06-15',
        'total_views' => 50
      ]
    );

    DB::table('website_stats')->insert(
      [
        'website_id' => 1,
        'view_date' => '2022-06-14',
        'total_views' => 22
      ]
    );

    DB::table('website_stats')->insert(
      [
        'website_id' => 1,
        'view_date' => '2022-06-13',
        'total_views' => 0
      ]
    );

    DB::table('website_stats')->insert(
      [
        'website_id' => 1,
        'view_date' => '2022-06-12',
        'total_views' => 72
      ]
    );

    DB::table('website_stats')->insert(
      [
        'website_id' => 1,
        'view_date' => '2022-06-11',
        'total_views' => 33
      ]
    );

    DB::table('website_stats')->insert(
      [
        'website_id' => 1,
        'view_date' => '2022-06-10',
        'total_views' => 11
      ]
    );

    DB::table('website_stats')->insert(
      [
        'website_id' => 1,
        'view_date' => '2022-06-09',
        'total_views' => 10
      ]
    );
  }
}
