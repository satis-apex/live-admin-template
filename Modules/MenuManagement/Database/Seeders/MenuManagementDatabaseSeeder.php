<?php
namespace Modules\MenuManagement\Database\Seeders;

use Illuminate\Database\Seeder;

class MenuManagementDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(
            [
                MenuSeeder::class,
                MenuLinkSeeder::class,
            ]
        );
    }
}
