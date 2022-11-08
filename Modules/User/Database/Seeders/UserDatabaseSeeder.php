<?php
namespace Modules\User\Database\Seeders;

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
