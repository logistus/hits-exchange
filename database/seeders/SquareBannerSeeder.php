<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SquareBannerSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('square_banners')->insert([
      'user_id' => 2,
      'image_url' => 'https://hungryforhits.com/getimg.php?id=6',
      'target_url' => 'https://hungryforhits.com/?rid=17',
      'assigned' => 10000,
      'status' => 'Active'
    ]);

    DB::table('square_banners')->insert([
      'user_id' => 2,
      'image_url' => 'https://www.easyhits4u.com/img/banners/125x125_1.gif',
      'target_url' => 'http://www.easyhits4u.com/?ref=logistus',
      'assigned' => 10000,
      'status' => 'Active'
    ]);
    DB::table('square_banners')->insert([
      'user_id' => 2,
      'image_url' => 'https://hit2hit.com/refbanners/125c.png',
      'target_url' => 'https://hit2hit.com/?rid=145939',
      'assigned' => 10000,
      'status' => 'Active'
    ]);
    DB::table('square_banners')->insert([
      'user_id' => 2,
      'image_url' => 'https://lionhits.com/getimg.php?id=2',
      'target_url' => 'https://lionhits.com/?rid=263',
      'assigned' => 10000,
      'status' => 'Active'
    ]);

    DB::table('square_banners')->insert([
      'user_id' => 2,
      'image_url' => 'https://tiger-hits.com/getimg.php?id=2',
      'target_url' => 'https://tiger-hits.com/?rid=518',
      'assigned' => 10000,
      'status' => 'Active'
    ]);
  }
}
