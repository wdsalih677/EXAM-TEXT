<?php

namespace Database\Factories;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attempt>
 */
class AttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startedAt = now();

        return [
            'trainee_id' => User::factory()->trainee(),
            'exam_id' => Exam::factory(),
            'started_at' => $startedAt,
            'submitted_at' => null,
            'status' => AttemptStatus::InProgress,
            'score' => null,
            'current_question_index' => 0,
            'current_question_started_at' => $startedAt,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AttemptStatus::Completed,
            'submitted_at' => now(),
            'score' => '70.00',
        ]);
    }
}
