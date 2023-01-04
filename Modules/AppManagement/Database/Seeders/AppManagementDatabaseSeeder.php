<?php
namespace Modules\AppManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AppManagementDatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();
        $this->call(
            [
                ApplicationInfoSeeder::class,
            ]
        );
        // $this->call("OthersTableSeeder");
    }
}
