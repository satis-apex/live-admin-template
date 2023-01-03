<?php
namespace Modules\MenuManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = file_get_contents(base_path('Modules/MenuManagement') . '/Database/Seeds/menus.sql');

        DB::statement($sql);
    }
}
