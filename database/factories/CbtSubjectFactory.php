<?php

namespace Database\Factories;

use App\Models\CbtSubject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CbtSubject>
 */
class CbtSubjectFactory extends Factory
{
    protected $model = CbtSubject::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->lexify('??')),
            'name' => fake()->words(2, true),
            'topic' => fake()->words(3, true),
            'items_count' => fake()->numberBetween(100, 3000),
            'difficulty' => fake()->randomElement(['Hard', 'Medium', 'Easy']),
        ];
    }

    public function hard(): static
    {
        return $this->state(['difficulty' => 'Hard']);
    }

    public function medium(): static
    {
        return $this->state(['difficulty' => 'Medium']);
    }

    public function easy(): static
    {
        return $this->state(['difficulty' => 'Easy']);
    }
}
