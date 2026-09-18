<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Attempt;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Answer>
 */
class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'attempt_id' => Attempt::factory(),
            'question_id' => Question::factory(),
            'selected_option_id' => Option::factory(),
            'is_timed_out' => false,
            'question_started_at' => now(),
            'answered_at' => now(),
        ];
    }

    public function timedOut(): static
    {
        return $this->state(fn (array $attributes) => [
            'selected_option_id' => null,
            'is_timed_out' => true,
            'answered_at' => now(),
        ]);
    }
}
