<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    DB::table('banners')->insert(
      [
        'user_id' => 2,
        'image_url' => 'https://www.traffic-splash.com/getimg.php?id=1',
        'target_url' => 'https://traffic-splash.com/?rid=168301',
        'assigned' => 10000,
        'status' => 'Active'
      ]
    );

    DB::table('banners')->insert(
      [
        'user_id' => 2,
        'image_url' => 'https://www.hungryforhits.com/getimg.php?id=1',
        'target_url' => 'https://hungryforhits.com/?rid=17',
        'assigned' => 10000,
        'status' => 'Active'
      ]
    );

    DB::table('banners')->insert(
      [
        'user_id' => 2,
        'image_url' => 'https://tezzers.com/getimg.php?id=6',
        'target_url' => 'https://tezzers.com/?rid=107246',
        'assigned' => 10000,
        'status' => 'Active'
      ]
    );
  }
}
