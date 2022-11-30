<?php

namespace Modules\DataTable\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\DataTable\Models\DataTable;

class DataTableDatabaseSeeder extends Seeder
{
    public function run()
    {
        // Model::unguard();
        DataTable::factory()
        ->count(5000)
        ->create();
        // $this->call("OthersTableSeeder");
    }
}
