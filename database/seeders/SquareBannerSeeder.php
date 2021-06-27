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
		DB::table('square_banners')->insert(
			[
				'user_id' => 1,
				'image_url' => 'https://hungryforhits.com/getimg.php?id=6',
				'target_url' => 'https://hungryforhits.com/?rid=17',
				'assigned' => 10000,
				'status' => 'Active'
			]
		);
	}
}
