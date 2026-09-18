<?php

use App\Enums\AttemptStatus;
use App\Enums\QuestionCategory;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('the server calculates the final score from stored answers', function () {
    $lawyer = User::factory()->lawyer()->create();
    $exam = Exam::factory()->for($lawyer, 'lawyer')->create(['seconds_per_question' => 60]);
    $trainee = User::factory()->trainee()->create();

    $first = Question::factory()->for($exam)->create([
        'order' => 1,
        'category' => QuestionCategory::Custody,
    ]);
    $firstCorrect = Option::factory()->for($first)->correct()->create();
    Option::factory()->for($first)->create();

    $second = Question::factory()->for($exam)->create([
        'order' => 2,
        'category' => QuestionCategory::Divorce,
    ]);
    Option::factory()->for($second)->correct()->create();
    $secondWrong = Option::factory()->for($second)->create();

    $this->actingAs($trainee)
        ->post(route('trainee.exams.start', $exam))
        ->assertRedirect();

    $attempt = Attempt::query()->first();

    expect($attempt)->not->toBeNull();

    $this->actingAs($trainee)->post(route('trainee.attempts.answer', $attempt), [
        'option_id' => $firstCorrect->id,
    ]);
    $this->actingAs($trainee)->post(route('trainee.attempts.next', $attempt));
    $this->actingAs($trainee)->post(route('trainee.attempts.answer', $attempt), [
        'option_id' => $secondWrong->id,
    ]);
    $this->actingAs($trainee)
        ->post(route('trainee.attempts.next', $attempt))
        ->assertRedirect(route('trainee.attempts.result', $attempt));

    $attempt->refresh();

    expect($attempt->status)->toBe(AttemptStatus::Completed)
        ->and((float) $attempt->score)->toBe(50.0);

    $this->actingAs($trainee)
        ->get(route('trainee.attempts.result', $attempt))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('trainee/exam/result')
            ->where('score', 50)
            ->where('correct_answers', 1)
            ->where('wrong_answers', 1)
            ->where('unanswered', 0)
            ->has('categories', 2)
            ->has('questions.0.explanation')
            ->has('questions.0.correct_option_text'));
});

test('timed out questions are counted as unanswered', function () {
    $exam = Exam::factory()->withQuestions(1)->create(['seconds_per_question' => 15]);
    $trainee = User::factory()->trainee()->create();

    $this->actingAs($trainee)->post(route('trainee.exams.start', $exam));
    $attempt = Attempt::query()->first();

    $this->travel(16)->seconds();

    $this->actingAs($trainee)->get(route('trainee.attempts.show', $attempt));

    $attempt->refresh();

    expect($attempt->status)->toBe(AttemptStatus::Completed)
        ->and((float) $attempt->score)->toBe(0.0);

    $this->actingAs($trainee)
        ->get(route('trainee.attempts.result', $attempt))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('unanswered', 1)
            ->where('questions.0.status', 'unanswered'));
});

test('a trainee cannot change answers after the attempt is completed', function () {
    $exam = Exam::factory()->withQuestions(1)->create();
    $trainee = User::factory()->trainee()->create();

    $this->actingAs($trainee)->post(route('trainee.exams.start', $exam));
    $attempt = Attempt::query()->first();
    $option = $exam->questions()->first()->options()->first();

    $this->actingAs($trainee)->post(route('trainee.attempts.answer', $attempt), [
        'option_id' => $option->id,
    ]);
    $this->actingAs($trainee)->post(route('trainee.attempts.next', $attempt));

    $this->actingAs($trainee)
        ->post(route('trainee.attempts.answer', $attempt), [
            'option_id' => $option->id,
        ])
        ->assertForbidden();
});
