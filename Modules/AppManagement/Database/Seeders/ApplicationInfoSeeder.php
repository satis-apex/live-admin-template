<?php
namespace Modules\AppManagement\Database\Seeders;

use Modules\AppManagement\Models\ApplicationInfo;
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
            'address' => 'address',
            'contact' => '0123456789',
            'primary_color' => '#009EF7',
            'primary_light_color' => '#0AA9FF',
            'primary_dark_color' => '#0071AD',
            'complementary_color' => '#FFBA00',
        ]);
    }
}
