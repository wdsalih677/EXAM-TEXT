<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ExamService
{
    /**
     * @param  array{title: string, seconds_per_question: int, questions: list<array<string, mixed>>}  $data
     */
    public function create(User $lawyer, array $data): Exam
    {
        return DB::transaction(function () use ($lawyer, $data): Exam {
            $exam = Exam::query()->create([
                'lawyer_id' => $lawyer->id,
                'title' => $data['title'],
                'seconds_per_question' => $data['seconds_per_question'],
            ]);

            $this->syncQuestions($exam, $data['questions']);

            return $exam->load('questions.options');
        });
    }

    /**
     * @param  array{title: string, seconds_per_question: int, questions: list<array<string, mixed>>}  $data
     */
    public function update(Exam $exam, array $data): Exam
    {
        return DB::transaction(function () use ($exam, $data): Exam {
            $exam->update([
                'title' => $data['title'],
                'seconds_per_question' => $data['seconds_per_question'],
            ]);

            $exam->questions()->delete();
            $this->syncQuestions($exam, $data['questions']);

            return $exam->refresh()->load('questions.options');
        });
    }

    /**
     * @param  list<array<string, mixed>>  $questions
     */
    private function syncQuestions(Exam $exam, array $questions): void
    {
        foreach ($questions as $index => $questionData) {
            $question = $exam->questions()->create([
                'scenario_text' => $questionData['scenario_text'],
                'category' => $questionData['category'],
                'explanation' => $questionData['explanation'],
                'order' => $index + 1,
            ]);

            foreach ($questionData['options'] as $optionData) {
                $question->options()->create([
                    'text' => $optionData['text'],
                    'is_correct' => (bool) $optionData['is_correct'],
                ]);
            }
        }
    }
}
