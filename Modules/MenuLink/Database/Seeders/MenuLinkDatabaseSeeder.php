<?php
namespace Modules\MenuLink\Database\Seeders;

use Illuminate\Database\Seeder;

class MenuLinkDatabaseSeeder extends Seeder
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
