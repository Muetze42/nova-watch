<?php

namespace Database\Factories;

use App\Enums\AlertProviderEnum;
use App\Models\Alert;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AlertFactory extends Factory
{
    protected $model = Alert::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'active' => fake()->boolean(75),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'provider' => fake()->randomElement(AlertProviderEnum::class),
        ];
    }
}
