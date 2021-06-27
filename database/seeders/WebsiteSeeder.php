<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteSeeder extends Seeder
{
	/**
	 * Run the database seeds.
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
	}
}
