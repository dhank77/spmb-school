<?php

namespace Database\Factories;

use App\Models\AdmissionWave;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AdmissionWave>
 */
class AdmissionWaveFactory extends Factory
{
    protected $model = AdmissionWave::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalQuota = fake()->numberBetween(80, 200);
        $remaining = fake()->numberBetween(0, $totalQuota);

        return [
            'name' => 'Gelombang '.fake()->numberBetween(1, 5),
            'period' => fake()->monthName().' - '.fake()->monthName().' '.fake()->year(),
            'registration_cost' => fake()->randomElement([250000, 350000, 500000]),
            'total_quota' => $totalQuota,
            'remaining_quota' => $remaining,
            'status' => fake()->randomElement(['closed', 'active', 'upcoming']),
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }

    /**
     * Wave that is closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
            'remaining_quota' => 0,
        ]);
    }

    /**
     * Wave that is currently active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'remaining_quota' => fake()->numberBetween(10, 80),
        ]);
    }

    /**
     * Wave that is upcoming.
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'upcoming',
        ]);
    }
}
