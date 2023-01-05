<?php
namespace Modules\EmployeeManagement\Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Modules\EmployeeManagement\Models\Employee;

class EmployeeManagementDatabaseSeeder extends Seeder
{
    public function run()
    {
        $employeeInfo = Employee::create([
            'first_name' => 'satish',
            'last_name' => 'maharjan',
            'email' => 'employee@employee.com',
            'joined_date' => Carbon::now(),
            'phone' => '0934234235',
            'gender' => 'Male'
        ]);

        $user = $employeeInfo->account()->create([
            'email' => 'employee@employee.com',
            'password' => 'password',
            'email_verified_at' => Carbon::now(),
        ]);
        $user->assignRole('Employee');

        // $this->call("OthersTableSeeder");
    }
}
