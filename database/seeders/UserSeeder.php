<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\userInfo;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$userInfo = userInfo::create([
			'first_name' => 'satish',
			'last_name' => 'maharjan',
			// 'email' => 'admin@example.com',
			'joined_date' => Carbon::now(),
			"contact" => '0934234235',
			"gender" => 'male'
		]);

		$user = $userInfo->account()->create([
			'email' => 'admin@admin.com',
			'password' => 'password',
			'email_verified_at' => Carbon::now(),
		]);
		$user->assignRole('Su-Admin');
	}
}
