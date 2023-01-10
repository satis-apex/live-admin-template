<?php
namespace Modules\EventManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\EventManagement\Models\Announcement;

class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name()
        ];
    }
}