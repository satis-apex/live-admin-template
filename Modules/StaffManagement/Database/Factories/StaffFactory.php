<?php
namespace Modules\StaffManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\StaffManagement\Models\Staff;

class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name()
        ];
    }
}