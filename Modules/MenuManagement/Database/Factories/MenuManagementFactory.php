<?php
namespace Modules\MenuManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\MenuManagement\Models\MenuManagement;

class MenuManagementFactory extends Factory
{
    protected $model = MenuManagement::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name()
        ];
    }
}