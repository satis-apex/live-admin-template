<?php
namespace Modules\UserRole\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\UserRole\Models\UserRole;

class UserRoleFactory extends Factory
{
    protected $model = UserRole::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name()
        ];
    }
}