<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

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
				'admin' => 1,
				'status' => 'Active'
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
				'status' => 'Active'
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
				'status' => 'Active',
			]
		);
	}
}
