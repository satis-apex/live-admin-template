<?php
namespace Modules\AppSetting\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\AppSetting\Models\AppSetting;

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