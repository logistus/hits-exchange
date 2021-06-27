<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TextAdSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
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
	}
}
