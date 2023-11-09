<?php

namespace Database\Factories;

use App\Models\Release;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReleaseFactory extends Factory
{
    protected $model = Release::class;

    public function definition(): array
    {
        $version = $this->faker->semver();

        return [
            'version' => $version,
            'version_id' => getVersionId($version),
            'major_version' => getMajorVersion($version),
            'notes' => $this->faker->paragraph(),
            'files' => $this->faker->words(),
            'published_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
