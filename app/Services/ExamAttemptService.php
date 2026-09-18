<?php

namespace App\Services;

use App\Enums\AttemptStatus;
use App\Exceptions\ExamAttemptException;
use App\Models\Answer;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExamAttemptService
{
    public function __construct(private ExamScoringService $scoring) {}

    public function start(User $trainee, Exam $exam): Attempt
    {
        if ($exam->questions()->doesntExist()) {
            throw ExamAttemptException::examUnavailable();
        }

        return DB::transaction(function () use ($trainee, $exam): Attempt {
            $attempt = Attempt::query()
                ->where('trainee_id', $trainee->id)
                ->where('exam_id', $exam->id)
                ->lockForUpdate()
                ->first();

            if ($attempt === null) {
                $now = now();

                return Attempt::query()->create([
                    'trainee_id' => $trainee->id,
                    'exam_id' => $exam->id,
                    'started_at' => $now,
                    'status' => AttemptStatus::InProgress,
                    'current_question_index' => 0,
                    'current_question_started_at' => $now,
                ]);
            }

            if ($attempt->isCompleted()) {
                throw ExamAttemptException::alreadyCompleted($attempt);
            }

            return $attempt;
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function takePageProps(Attempt $attempt): array
    {
        $attempt = $this->syncExpiredQuestion($attempt);

        if ($attempt->isCompleted()) {
            throw ExamAttemptException::alreadyCompleted($attempt);
        }

        $questions = $this->orderedQuestions($attempt);
        $question = $this->currentQuestion($attempt, $questions);
        $answer = $attempt->answers->firstWhere('question_id', $question->id);
        $now = now();
        $deadline = $this->deadline($attempt);

        return [
            'attempt' => [
                'id' => $attempt->id,
                'status' => $attempt->status->value,
                'current_question_index' => $attempt->current_question_index,
            ],
            'exam' => [
                'id' => $attempt->exam->id,
                'title' => $attempt->exam->title,
                'seconds_per_question' => $attempt->exam->seconds_per_question,
            ],
            'question' => [
                'id' => $question->id,
                'number' => $attempt->current_question_index + 1,
                'total' => $questions->count(),
                'scenario_text' => $question->scenario_text,
                'category_label' => $question->category->label(),
                'options' => $question->options
                    ->map(fn (Option $option): array => [
                        'id' => $option->id,
                        'text' => $option->text,
                    ])
                    ->values()
                    ->all(),
            ],
            'selected_option_id' => $answer?->selected_option_id,
            'server_now' => $now->toIso8601String(),
            'question_started_at' => $attempt->current_question_started_at->toIso8601String(),
            'question_deadline' => $deadline->toIso8601String(),
            'remaining_seconds' => $this->remainingSeconds($now, $deadline),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function resultPageProps(Attempt $attempt): array
    {
        if (! $attempt->isCompleted()) {
            throw ExamAttemptException::unavailable();
        }

        $attempt->loadMissing(['exam.questions.options', 'answers.selectedOption']);

        $summary = $this->scoring->calculate($attempt);
        $answers = $attempt->answers->keyBy('question_id');

        $reviews = $attempt->exam->questions->map(function (Question $question) use ($answers): array {
            $answer = $answers->get($question->id);
            $correct = $question->correctOption();
            $selected = $answer?->selectedOption;
            $status = 'unanswered';

            if ($selected !== null) {
                $status = $selected->is_correct ? 'correct' : 'wrong';
            }

            return [
                'id' => $question->id,
                'order' => $question->order,
                'scenario_text' => $question->scenario_text,
                'category_label' => $question->category->label(),
                'explanation' => $question->explanation,
                'selected_option_text' => $selected?->text,
                'correct_option_text' => $correct?->text,
                'status' => $status,
            ];
        })->values()->all();

        return [
            'attempt' => [
                'id' => $attempt->id,
                'submitted_at' => $attempt->submitted_at?->toIso8601String(),
            ],
            'exam' => [
                'id' => $attempt->exam->id,
                'title' => $attempt->exam->title,
            ],
            ...$summary,
            'questions' => $reviews,
        ];
    }

    public function saveAnswer(Attempt $attempt, int $optionId): Attempt
    {
        $outcome = DB::transaction(function () use ($attempt, $optionId): array {
            $attempt = $this->lockAttempt($attempt);
            $this->assertInProgress($attempt);

            if ($this->hasCurrentQuestionExpired($attempt)) {
                $this->resolveExpiredCurrentQuestion($attempt);

                return ['attempt' => $attempt->refresh(), 'timed_out' => true];
            }

            $question = $this->currentQuestion($attempt);
            $option = Option::query()->whereKey($optionId)->first();

            if ($option === null || $option->question_id !== $question->id) {
                throw ExamAttemptException::invalidOption($attempt);
            }

            $existing = $this->answerForQuestion($attempt, $question);

            if ($existing !== null && $existing->isResolved()) {
                return ['attempt' => $attempt, 'timed_out' => false];
            }

            Answer::query()->updateOrCreate(
                [
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                ],
                [
                    'selected_option_id' => $option->id,
                    'is_timed_out' => false,
                    'question_started_at' => $attempt->current_question_started_at,
                    'answered_at' => now(),
                ],
            );

            return ['attempt' => $attempt->refresh()->load(['exam.questions.options', 'answers']), 'timed_out' => false];
        });

        if ($outcome['timed_out'] === true) {
            throw ExamAttemptException::timedOut($outcome['attempt']);
        }

        return $outcome['attempt'];
    }

    public function timeoutCurrentQuestion(Attempt $attempt, int $questionId): Attempt
    {
        $outcome = DB::transaction(function () use ($attempt, $questionId): array {
            $attempt = $this->lockAttempt($attempt);
            $this->assertInProgress($attempt);

            $question = $this->currentQuestion($attempt);

            if ($question->id !== $questionId) {
                return ['attempt' => $attempt, 'timed_out' => false];
            }

            if (! $this->hasCurrentQuestionExpired($attempt)) {
                return ['attempt' => $attempt, 'timed_out' => false];
            }

            $wasResolved = $this->currentQuestionIsResolved($attempt);
            $this->resolveExpiredCurrentQuestion($attempt);

            return ['attempt' => $attempt->refresh(), 'timed_out' => ! $wasResolved];
        });

        if ($outcome['timed_out'] === true) {
            throw ExamAttemptException::timedOut($outcome['attempt']);
        }

        return $outcome['attempt'];
    }

    public function moveToNextQuestion(Attempt $attempt): Attempt
    {
        $outcome = DB::transaction(function () use ($attempt): array {
            $attempt = $this->lockAttempt($attempt);
            $this->assertInProgress($attempt);

            if ($this->hasCurrentQuestionExpired($attempt)) {
                $wasResolved = $this->currentQuestionIsResolved($attempt);
                $this->resolveExpiredCurrentQuestion($attempt);

                return ['attempt' => $attempt->refresh(), 'timed_out' => ! $wasResolved];
            }

            if (! $this->currentQuestionIsResolved($attempt)) {
                throw ExamAttemptException::answerRequired($attempt);
            }

            $this->advance($attempt);

            return ['attempt' => $attempt->refresh(), 'timed_out' => false];
        });

        $synced = $outcome['attempt'];

        if ($outcome['timed_out'] === true && $synced->isInProgress()) {
            throw ExamAttemptException::timedOut($synced);
        }

        return $synced;
    }

    public function syncExpiredQuestion(Attempt $attempt): Attempt
    {
        return DB::transaction(function () use ($attempt): Attempt {
            $attempt = $this->lockAttempt($attempt);

            if ($attempt->isCompleted()) {
                return $attempt;
            }

            if ($this->hasCurrentQuestionExpired($attempt)) {
                $this->resolveExpiredCurrentQuestion($attempt);
                $attempt = $attempt->refresh();
            }

            return $attempt->load(['exam.questions.options', 'answers']);
        });
    }

    private function lockAttempt(Attempt $attempt): Attempt
    {
        return Attempt::query()
            ->whereKey($attempt->id)
            ->lockForUpdate()
            ->with(['exam.questions.options', 'answers.selectedOption'])
            ->firstOrFail();
    }

    private function assertInProgress(Attempt $attempt): void
    {
        if ($attempt->isCompleted()) {
            throw ExamAttemptException::alreadyCompleted($attempt);
        }
    }

    /**
     * @param  Collection<int, Question>|null  $questions
     */
    private function currentQuestion(Attempt $attempt, ?Collection $questions = null): Question
    {
        $questions ??= $this->orderedQuestions($attempt);
        $question = $questions->get($attempt->current_question_index);

        if (! $question instanceof Question) {
            throw ExamAttemptException::unavailable();
        }

        return $question;
    }

    /**
     * @return Collection<int, Question>
     */
    private function orderedQuestions(Attempt $attempt): Collection
    {
        $attempt->loadMissing('exam.questions.options');

        return $attempt->exam->questions->values();
    }

    private function hasCurrentQuestionExpired(Attempt $attempt): bool
    {
        return $this->deadline($attempt)->lte(now());
    }

    private function deadline(Attempt $attempt): CarbonInterface
    {
        return $attempt->current_question_started_at
            ->addSeconds($attempt->exam->seconds_per_question);
    }

    private function remainingSeconds(CarbonInterface $now, CarbonInterface $deadline): int
    {
        return max(0, $deadline->getTimestamp() - $now->getTimestamp());
    }

    private function currentQuestionIsResolved(Attempt $attempt): bool
    {
        $question = $this->currentQuestion($attempt);
        $answer = $this->answerForQuestion($attempt, $question);

        return $answer !== null && $answer->isResolved();
    }

    private function answerForQuestion(Attempt $attempt, Question $question): ?Answer
    {
        $attempt->loadMissing('answers');

        $answer = $attempt->answers->firstWhere('question_id', $question->id);

        return $answer instanceof Answer ? $answer : null;
    }

    private function resolveExpiredCurrentQuestion(Attempt $attempt): void
    {
        if (! $this->currentQuestionIsResolved($attempt)) {
            $this->markCurrentQuestionTimedOut($attempt);
        }

        $this->advance($attempt);
    }

    private function markCurrentQuestionTimedOut(Attempt $attempt): void
    {
        $question = $this->currentQuestion($attempt);

        Answer::query()->updateOrCreate(
            [
                'attempt_id' => $attempt->id,
                'question_id' => $question->id,
            ],
            [
                'selected_option_id' => null,
                'is_timed_out' => true,
                'question_started_at' => $attempt->current_question_started_at,
                'answered_at' => now(),
            ],
        );

        $attempt->unsetRelation('answers');
        $attempt->load('answers');
    }

    private function advance(Attempt $attempt): void
    {
        $total = $this->orderedQuestions($attempt)->count();
        $nextIndex = $attempt->current_question_index + 1;

        if ($nextIndex >= $total) {
            $this->complete($attempt);

            return;
        }

        $attempt->forceFill([
            'current_question_index' => $nextIndex,
            'current_question_started_at' => now(),
        ])->save();
    }

    private function complete(Attempt $attempt): void
    {
        $attempt->load(['exam.questions.options', 'answers.selectedOption']);

        $summary = $this->scoring->calculate($attempt);

        $attempt->forceFill([
            'status' => AttemptStatus::Completed,
            'submitted_at' => now(),
            'score' => $summary['score'],
        ])->save();
    }
}
