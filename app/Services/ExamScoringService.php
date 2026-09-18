<?php

namespace App\Services;

use App\Enums\QuestionCategory;
use App\Models\Answer;
use App\Models\Attempt;
use App\Models\Question;

class ExamScoringService
{
    /**
     * @return array{
     *     total_questions: int,
     *     correct_answers: int,
     *     wrong_answers: int,
     *     unanswered: int,
     *     score: float,
     *     categories: list<array{category: string, label: string, total: int, correct: int, wrong: int, unanswered: int, percentage: float}>
     * }
     */
    public function calculate(Attempt $attempt): array
    {
        $attempt->loadMissing(['exam.questions.options', 'answers.selectedOption']);

        $questions = $attempt->exam->questions;
        $answers = $attempt->answers->keyBy('question_id');

        $totals = $this->emptyBucket();

        $categoryStats = [];

        foreach (QuestionCategory::cases() as $category) {
            $categoryStats[$category->value] = $this->emptyBucket();
        }

        foreach ($questions as $question) {
            $bucket = $this->scoreQuestion($question, $answers->get($question->id));
            $this->addBucket($totals, $bucket);

            $categoryKey = $question->category->value;
            $this->addBucket($categoryStats[$categoryKey], $bucket);
        }

        $categories = [];

        foreach (QuestionCategory::cases() as $category) {
            $bucket = $categoryStats[$category->value];

            if ($bucket['total'] === 0) {
                continue;
            }

            $categories[] = [
                'category' => $category->value,
                'label' => $category->label(),
                'total' => $bucket['total'],
                'correct' => $bucket['correct'],
                'wrong' => $bucket['wrong'],
                'unanswered' => $bucket['unanswered'],
                'percentage' => $this->percentage($bucket['correct'], $bucket['total']),
            ];
        }

        return [
            'total_questions' => $totals['total'],
            'correct_answers' => $totals['correct'],
            'wrong_answers' => $totals['wrong'],
            'unanswered' => $totals['unanswered'],
            'score' => $this->percentage($totals['correct'], $totals['total']),
            'categories' => $categories,
        ];
    }

    /**
     * @return array{total: int, correct: int, wrong: int, unanswered: int}
     */
    private function emptyBucket(): array
    {
        return [
            'total' => 0,
            'correct' => 0,
            'wrong' => 0,
            'unanswered' => 0,
        ];
    }

    /**
     * @param  array{total: int, correct: int, wrong: int, unanswered: int}  $target
     * @param  array{total: int, correct: int, wrong: int, unanswered: int}  $source
     */
    private function addBucket(array &$target, array $source): void
    {
        $target['total'] += $source['total'];
        $target['correct'] += $source['correct'];
        $target['wrong'] += $source['wrong'];
        $target['unanswered'] += $source['unanswered'];
    }

    /**
     * @return array{total: int, correct: int, wrong: int, unanswered: int}
     */
    private function scoreQuestion(Question $question, mixed $answer): array
    {
        $bucket = $this->emptyBucket();
        $bucket['total'] = 1;

        if (! $answer instanceof Answer || $answer->selected_option_id === null) {
            $bucket['unanswered'] = 1;

            return $bucket;
        }

        $selected = $answer->selectedOption;

        if ($selected !== null && $selected->is_correct) {
            $bucket['correct'] = 1;

            return $bucket;
        }

        $bucket['wrong'] = 1;

        return $bucket;
    }

    private function percentage(int $correct, int $total): float
    {
        if ($total === 0) {
            return 0.0;
        }

        return round(($correct / $total) * 100, 2);
    }
}
