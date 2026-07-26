<?php

namespace Database\Factories;

use App\Models\CbtExam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CbtExam>
 */
class CbtExamFactory extends Factory
{
    protected $model = CbtExam::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true).' Exam',
            'date' => fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'session' => fake()->randomElement(['Morning (08:00)', 'Noon (11:00)', 'Afternoon (14:00)']),
            'room' => fake()->randomElement(['Lab A-01', 'Lab A-02', 'Hall C', 'Library W']),
        ];
    }
}
