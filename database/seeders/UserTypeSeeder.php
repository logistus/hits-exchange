<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('user_types')->insert([
			'name' => 'Free',
			'surf_timer' => 10,
			'surf_ratio' => 0.5,
			'max_websites' => 5,
			'max_banners' => 5,
			'max_square_banners' => 5,
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
			'max_websites' => 15,
			'max_banners' => 15,
			'max_square_banners' => 15,
			'max_texts' => 15,
			'min_auto_assign' => 0,
			'credits_to_banner' => 100,
			'credits_to_text' => 200,
			'credit_reward_ratio' => 25,
			'commission_ratio' => 40,
			'default_text_ad_color' => '#FFFFFF',
			'default_text_ad_bg_color' => '#1246e2',
		]);
	}
}
