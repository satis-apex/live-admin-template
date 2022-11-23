<?php

namespace Database\Seeders;

use Modules\AppSetting\Models\ApplicationInfo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ApplicationInfoSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		ApplicationInfo::create([
			'title' => 'admin template',
			'year' => date('Y'),
			'email' => 'admin@example.com',
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
			"address" => 'address',
		]);
	}
}
