<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpgradePricesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('upgrade_prices')->insert([
      'user_type_id' => 2,
      'time_type' => 'Month',
      'time_amount' => 1,
      'price' => 5
    ]);

    DB::table('upgrade_prices')->insert([
      'user_type_id' => 2,
      'time_type' => 'Month',
      'time_amount' => 3,
      'price' => 10
    ]);

    DB::table('upgrade_prices')->insert([
      'user_type_id' => 2,
      'time_type' => 'Month',
      'time_amount' => 6,
      'price' => 25
    ]);

    DB::table('upgrade_prices')->insert([
      'user_type_id' => 2,
      'time_type' => 'Year',
      'time_amount' => 1,
      'price' => 50
    ]);
  }
}
