<?php
namespace Modules\UserManagement\Database\Seeders;

use Illuminate\Database\Seeder;

class UserDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(
            [
                RoleAndPermissionSeeder::class,
                UserSeeder::class,
            ]
        );
    }
}
