<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;

/**
 * @extends Factory<Exam>
 */
class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lawyer_id' => User::factory()->lawyer(),
            'title' => fake()->sentence(4),
            'seconds_per_question' => 60,
        ];
    }

    public function withQuestions(int $count = 2): static
    {
        return $this->afterCreating(function (Exam $exam) use ($count): void {
            Question::factory()
                ->count($count)
                ->for($exam)
                ->sequence(fn (Sequence $sequence) => ['order' => $sequence->index + 1])
                ->create()
                ->each(function (Question $question): void {
                    Option::factory()->for($question)->create(['text' => 'الخيار الأول', 'is_correct' => true]);
                    Option::factory()->for($question)->create(['text' => 'الخيار الثاني', 'is_correct' => false]);
                    Option::factory()->for($question)->create(['text' => 'الخيار الثالث', 'is_correct' => false]);
                    Option::factory()->for($question)->create(['text' => 'الخيار الرابع', 'is_correct' => false]);
                });
        });
    }
}
