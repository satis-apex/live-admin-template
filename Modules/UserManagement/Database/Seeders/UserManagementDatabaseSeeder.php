<?php
namespace Modules\UserManagement\Database\Seeders;

use Illuminate\Database\Seeder;

class UserManagementDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(
            [
                RoleAndPermissionSeeder::class,
                UserSeeder::class,
                NotificationSeeder::class
            ]
        );
    }
}
