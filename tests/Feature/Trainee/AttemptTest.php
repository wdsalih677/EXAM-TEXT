<?php

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('a trainee can start an exam and resume the same attempt', function () {
    $exam = Exam::factory()->withQuestions(2)->create();
    $trainee = User::factory()->trainee()->create();

    $this->actingAs($trainee)
        ->post(route('trainee.exams.start', $exam))
        ->assertRedirect();

    $attempt = Attempt::query()->first();

    expect($attempt)->not->toBeNull()
        ->and($attempt->trainee_id)->toBe($trainee->id)
        ->and($attempt->current_question_index)->toBe(0)
        ->and($attempt->status)->toBe(AttemptStatus::InProgress);

    $this->actingAs($trainee)
        ->post(route('trainee.exams.start', $exam))
        ->assertRedirect(route('trainee.attempts.show', $attempt));

    expect(Attempt::query()->count())->toBe(1);
});

test('a lawyer cannot start an exam as a trainee', function () {
    $exam = Exam::factory()->withQuestions()->create();
    $lawyer = User::factory()->lawyer()->create();

    $this->actingAs($lawyer)
        ->post(route('trainee.exams.start', $exam))
        ->assertForbidden();
});

test('the take page does not expose correct answers or explanations', function () {
    $exam = Exam::factory()->withQuestions(1)->create();
    $trainee = User::factory()->trainee()->create();

    $this->actingAs($trainee)->post(route('trainee.exams.start', $exam));
    $attempt = Attempt::query()->first();

    $this->actingAs($trainee)
        ->get(route('trainee.attempts.show', $attempt))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('trainee/exam/take')
            ->has('question.options.0.id')
            ->has('question.options.0.text')
            ->missing('question.explanation')
            ->missing('question.options.0.is_correct')
            ->missing('question.correct_option_id'));
});

test('a trainee can save a valid answer for the current question', function () {
    $exam = Exam::factory()->withQuestions(1)->create();
    $trainee = User::factory()->trainee()->create();

    $this->actingAs($trainee)->post(route('trainee.exams.start', $exam));
    $attempt = Attempt::query()->first();
    $option = $exam->questions()->first()->options()->first();

    $this->actingAs($trainee)
        ->post(route('trainee.attempts.answer', $attempt), [
            'option_id' => $option->id,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('answers', [
        'attempt_id' => $attempt->id,
        'question_id' => $exam->questions()->first()->id,
        'selected_option_id' => $option->id,
        'is_timed_out' => false,
    ]);
});

test('a trainee cannot answer with an option from another question', function () {
    $exam = Exam::factory()->withQuestions(2)->create();
    $trainee = User::factory()->trainee()->create();

    $this->actingAs($trainee)->post(route('trainee.exams.start', $exam));
    $attempt = Attempt::query()->first();
    $otherOption = $exam->questions()->orderBy('order')->get()->last()->options()->first();

    $this->actingAs($trainee)
        ->post(route('trainee.attempts.answer', $attempt), [
            'option_id' => $otherOption->id,
        ]);

    $this->assertDatabaseMissing('answers', [
        'attempt_id' => $attempt->id,
        'selected_option_id' => $otherOption->id,
    ]);
});

test('duplicate answers for the same question are ignored', function () {
    $exam = Exam::factory()->withQuestions(1)->create();
    $trainee = User::factory()->trainee()->create();

    $this->actingAs($trainee)->post(route('trainee.exams.start', $exam));
    $attempt = Attempt::query()->first();
    $options = $exam->questions()->first()->options()->orderBy('id')->get();

    $this->actingAs($trainee)->post(route('trainee.attempts.answer', $attempt), [
        'option_id' => $options[0]->id,
    ]);

    $this->actingAs($trainee)->post(route('trainee.attempts.answer', $attempt), [
        'option_id' => $options[1]->id,
    ]);

    expect($attempt->answers()->count())->toBe(1)
        ->and($attempt->answers()->first()->selected_option_id)->toBe($options[0]->id);
});

test('a trainee cannot view another trainee attempt', function () {
    $exam = Exam::factory()->withQuestions()->create();
    $trainee = User::factory()->trainee()->create();
    $other = User::factory()->trainee()->create();

    $this->actingAs($trainee)->post(route('trainee.exams.start', $exam));
    $attempt = Attempt::query()->first();

    $this->actingAs($other)
        ->get(route('trainee.attempts.show', $attempt))
        ->assertForbidden();
});

test('an answer submitted after the server deadline is rejected and timed out', function () {
    $exam = Exam::factory()->withQuestions(2)->create(['seconds_per_question' => 30]);
    $trainee = User::factory()->trainee()->create();

    $this->actingAs($trainee)->post(route('trainee.exams.start', $exam));
    $attempt = Attempt::query()->first();
    $option = $exam->questions()->first()->options()->first();

    $this->travel(31)->seconds();

    $this->actingAs($trainee)
        ->post(route('trainee.attempts.answer', $attempt), [
            'option_id' => $option->id,
        ]);

    $answer = $attempt->answers()->where('question_id', $exam->questions()->first()->id)->first();

    expect($answer)->not->toBeNull()
        ->and($answer->is_timed_out)->toBeTrue()
        ->and($answer->selected_option_id)->toBeNull()
        ->and($attempt->fresh()->current_question_index)->toBe(1);
});

test('refreshing after timeout does not restart the same question', function () {
    $exam = Exam::factory()->withQuestions(2)->create(['seconds_per_question' => 20]);
    $trainee = User::factory()->trainee()->create();

    $this->actingAs($trainee)->post(route('trainee.exams.start', $exam));
    $attempt = Attempt::query()->first();

    $this->travel(21)->seconds();

    $this->actingAs($trainee)
        ->get(route('trainee.attempts.show', $attempt))
        ->assertOk();

    expect($attempt->fresh()->current_question_index)->toBe(1);
});

test('a trainee cannot skip to a later question', function () {
    $exam = Exam::factory()->withQuestions(3)->create();
    $trainee = User::factory()->trainee()->create();

    $this->actingAs($trainee)->post(route('trainee.exams.start', $exam));
    $attempt = Attempt::query()->first();

    $this->actingAs($trainee)
        ->post(route('trainee.attempts.next', $attempt));

    expect($attempt->fresh()->current_question_index)->toBe(0);
});
