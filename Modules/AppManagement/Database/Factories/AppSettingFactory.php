<?php
namespace Modules\AppManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\AppManagement\Models\AppSetting;

class AppSettingFactory extends Factory
{
    protected $model = AppSetting::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name()
        ];
    }
}