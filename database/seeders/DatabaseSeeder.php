<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                ApplicationInfoSeeder::class,
                MenuSeeder::class,
                MenuLinkSeeder::class,
                RoleAndPermissionSeeder::class,
                UserSeeder::class,
            ]
        );
    }
}
