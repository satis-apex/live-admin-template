<?php
namespace Modules\DataTable\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\DataTable\Models\DataTable;

class DataTableFactory extends Factory
{
    protected $model = DataTable::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'status' => $this->faker->randomElement(['active', 'in-active']),
            'address' => $this->faker->name(),
            'date' => $this->faker->date()
        ];
    }
}