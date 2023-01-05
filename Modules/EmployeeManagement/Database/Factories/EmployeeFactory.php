<?php
namespace Modules\EmployeeManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\EmployeeManagement\Models\Employee;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name()
        ];
    }
}
