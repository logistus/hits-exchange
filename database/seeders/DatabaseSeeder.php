<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    DB::table('websites')->insert(
      [
        'user_id' => 1,
        'url' => 'https://fastnfurioustraffic.com/supersplash.php?rid=1256',
        'status' => 'Active',
        'assigned' => 10000,
      ]
    );

    DB::table('websites')->insert(
      [
        'user_id' => 2,
        'url' => 'https://viraltrafficgames.com/splashpage.php?splashid=1&rid=1617',
        'status' => 'Active',
        'assigned' => 10000,
      ]
    );

    DB::table('websites')->insert(
      [
        'user_id' => 2,
        'url' => 'https://hit2hit.com/splash2.php/?rid=145939',
        'status' => 'Active',
        'assigned' => 10000
      ]
    );

    DB::table('websites')->insert(
      [
        'user_id' => 2,
        'url' => 'https://hungryforhits.com/supersplash.php?rid=17',
        'status' => 'Active',
        'assigned' => 10000
      ]
    );

    DB::table('websites')->insert(
      [
        'user_id' => 2,
        'url' => 'https://foodgame.surf/splashpage.php?splashid=5&rid=391',
        'status' => 'Active',
        'assigned' => 10000
      ]
    );

    DB::table('websites')->insert(
      [
        'user_id' => 2,
        'url' => 'https://tezzers.com/supersplash.php?rid=107246',
        'status' => 'Active',
        'assigned' => 10000
      ]
    );

    DB::table('banners')->insert(
      [
        'user_id' => 2,
        'image_url' => 'https://te-results.com/getimg.php?id=2',
        'target_url' => 'https://te-results.com/?rid=4762',
        'assigned' => 10000,
        'status' => 'Active'
      ]
    );

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

    DB::table('text_ads')->insert(
      [
        'user_id' => 2,
        'body' => 'Tezzers Traffic Power',
        'target_url' => 'https://tezzers.com/?rid=107246',
        'text_color' => '#FFFFFF',
        'bg_color' => '#1246e2',
        'text_bold' => 0,
        'assigned' => 10000,
        'status' => 'Active'
      ]
    );

    DB::table('text_ads')->insert(
      [
        'user_id' => 2,
        'body' => 'Hungry for Hits',
        'target_url' => 'https://hungryforhits.com/?rid=17',
        'text_color' => '#FFFFFF',
        'bg_color' => '#FF0000',
        'text_bold' => 1,
        'assigned' => 10000,
        'status' => 'Active'
      ]
    );

    DB::table('users')->insert(
      [
        'name' => 'Sinan',
        'surname' => 'Yilmaz',
        'email' => 'lordofclicking@gmail.com',
        'email_verified_at' => Carbon::now(),
        'username' => 'lordofclicking',
        'password' => Hash::make('password'),
        'country' => 'TR',
        'user_type' => 1,
        'credits' => 1000,
        'banner_imps' => 10000,
        'text_imps' => 10000,
        'join_date' => Carbon::now(),
      ]
    );

    DB::table('users')->insert(
      [
        'name' => 'John',
        'surname' => 'Doe',
        'email' => 'johndoe@gmail.com',
        'email_verified_at' => Carbon::now()->add(1, 'day'),
        'username' => 'johndoe',
        'country' => 'DE',
        'upline' => 1,
        'password' => Hash::make('john'),
        'user_type' => 1,
        'join_date' => Carbon::now()->add(1, 'day'),
      ]
    );

    DB::table('users')->insert(
      [
        'name' => 'Jane',
        'surname' => 'Doe',
        'email' => 'janedoe@gmail.com',
        'email_verified_at' => Carbon::now()->add(2, 'day'),
        'username' => 'janedoe',
        'country' => 'RU',
        'upline' => 1,
        'password' => Hash::make('jane'),
        'user_type' => 1,
        'join_date' => Carbon::now()->add(2, 'day'),
      ]
    );

    DB::table('user_types')->insert([
      'name' => 'Free',
      'surf_timer' => 10,
      'surf_ratio' => 0.5,
      'max_websites' => 5,
      'max_banners' => 5,
      'max_texts' => 5,
      'min_auto_assign' => 75,
      'credits_to_banner' => 10,
      'credits_to_text' => 20,
      'credit_reward_ratio' => 10,
      'commission_ratio' => 20,
      'default_text_ad_color' => '#FFFFFF',
      'default_text_ad_bg_color' => '#1246e2',
    ]);

    DB::table('user_types')->insert([
      'name' => 'Premium',
      'surf_timer' => 5,
      'surf_ratio' => 1,
      'max_websites' => 50,
      'max_banners' => 50,
      'max_texts' => 50,
      'min_auto_assign' => 0,
      'credits_to_banner' => 100,
      'credits_to_text' => 200,
      'credit_reward_ratio' => 25,
      'commission_ratio' => 40,
      'default_text_ad_color' => '#FFFFFF',
      'default_text_ad_bg_color' => '#1246e2',
    ]);

    DB::table('surfer_rewards')->insert([
      'minimum_page' => 100,
      'prize_amount' => 10,
      'prize_type' => 'Credits'
    ]);

    DB::table('surfer_rewards')->insert([
      'minimum_page' => 100,
      'prize_amount' => 25,
      'prize_type' => 'Banner Impressions'
    ]);

    DB::table('surfer_rewards')->insert([
      'minimum_page' => 100,
      'prize_amount' => 50,
      'prize_type' => 'Text Ad Impressions'
    ]);

    DB::table('surfer_rewards')->insert([
      'minimum_page' => 250,
      'prize_amount' => 20,
      'prize_type' => 'Credits'
    ]);

    DB::table('surfer_rewards')->insert([
      'minimum_page' => 250,
      'prize_amount' => 50,
      'prize_type' => 'Banner Impressions'
    ]);

    DB::table('surfer_rewards')->insert([
      'minimum_page' => 250,
      'prize_amount' => 100,
      'prize_type' => 'Text Ad Impressions'
    ]);
  }
}
