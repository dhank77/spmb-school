<?php

namespace Database\Factories;

use App\Models\CbtQuestion;
use App\Models\CbtSubject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CbtQuestion>
 */
class CbtQuestionFactory extends Factory
{
    protected $model = CbtQuestion::class;

    public function definition(): array
    {
        $correctAnswer = fake()->randomElement(['A', 'B', 'C', 'D']);

        return [
            'cbt_subject_id' => CbtSubject::factory(),
            'question_text' => fake()->paragraph(),
            'option_a' => fake()->sentence(3),
            'option_b' => fake()->sentence(3),
            'option_c' => fake()->sentence(3),
            'option_d' => fake()->sentence(3),
            'correct_answer' => $correctAnswer,
            'points' => fake()->randomElement([1, 2, 5]),
        ];
    }
}
