<?php

namespace Database\Factories;

use App\Enums\NotificationProviderEnum;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'active' => fake()->boolean(75),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'provider' => fake()->randomElement(NotificationProviderEnum::class),
        ];
    }
}
