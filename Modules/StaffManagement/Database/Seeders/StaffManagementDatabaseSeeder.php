<?php
namespace Modules\StaffManagement\Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Modules\StaffManagement\Models\Staff;

class StaffManagementDatabaseSeeder extends Seeder
{
    public function run()
    {
        $staffInfo = Staff::create([
            'first_name' => 'satish',
            'last_name' => 'maharjan',
            'email' => 'staff@staff.com',
            'joined_date' => Carbon::now(),
            'phone' => '0934234235',
            'gender' => 'male'
        ]);

        $user = $staffInfo->account()->create([
            'email' => 'staff@staff.com',
            'password' => 'password',
            'email_verified_at' => Carbon::now(),
        ]);
        $user->assignRole('Staff');

        // $this->call("OthersTableSeeder");
    }
}
