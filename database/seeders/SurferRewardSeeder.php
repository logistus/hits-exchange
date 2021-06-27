<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurferRewardSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

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
