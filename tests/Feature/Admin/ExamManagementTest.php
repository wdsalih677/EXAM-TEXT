<?php

use App\Enums\QuestionCategory;
use App\Models\Exam;
use App\Models\User;

function examPayload(array $overrides = []): array
{
    return array_merge([
        'title' => 'اختبار تجريبي',
        'seconds_per_question' => 60,
        'questions' => [
            [
                'scenario_text' => 'سيناريو السؤال الأول',
                'category' => QuestionCategory::Custody->value,
                'explanation' => 'تفسير الإجابة',
                'options' => [
                    ['text' => 'خيار أ', 'is_correct' => true],
                    ['text' => 'خيار ب', 'is_correct' => false],
                    ['text' => 'خيار ج', 'is_correct' => false],
                    ['text' => 'خيار د', 'is_correct' => false],
                ],
            ],
        ],
    ], $overrides);
}

test('trainees cannot create exams', function () {
    $this->actingAs(User::factory()->trainee()->create())
        ->post(route('admin.exams.store'), examPayload())
        ->assertForbidden();
});

test('lawyers can create an exam with questions and options', function () {
    $lawyer = User::factory()->lawyer()->create();

    $this->actingAs($lawyer)
        ->post(route('admin.exams.store'), examPayload())
        ->assertRedirect(route('admin.exams.index'));

    $exam = Exam::query()->first();

    expect($exam)->not->toBeNull()
        ->and($exam->lawyer_id)->toBe($lawyer->id)
        ->and($exam->questions)->toHaveCount(1)
        ->and($exam->questions->first()->options)->toHaveCount(4)
        ->and($exam->questions->first()->options->where('is_correct', true))->toHaveCount(1);
});

test('an exam is rejected when no correct answer is selected', function () {
    $lawyer = User::factory()->lawyer()->create();
    $payload = examPayload();
    $payload['questions'][0]['options'] = array_map(function (array $option): array {
        $option['is_correct'] = false;

        return $option;
    }, $payload['questions'][0]['options']);

    $this->actingAs($lawyer)
        ->post(route('admin.exams.store'), $payload)
        ->assertSessionHasErrors('questions.0.options');
});

test('an exam is rejected when multiple correct answers are selected', function () {
    $lawyer = User::factory()->lawyer()->create();
    $payload = examPayload();
    $payload['questions'][0]['options'][1]['is_correct'] = true;

    $this->actingAs($lawyer)
        ->post(route('admin.exams.store'), $payload)
        ->assertSessionHasErrors('questions.0.options');
});

test('an exam is rejected when a question has no scenario text', function () {
    $lawyer = User::factory()->lawyer()->create();
    $payload = examPayload();
    $payload['questions'][0]['scenario_text'] = '';

    $this->actingAs($lawyer)
        ->post(route('admin.exams.store'), $payload)
        ->assertSessionHasErrors('questions.0.scenario_text');
});

test('a lawyer cannot update another lawyer exam', function () {
    $owner = User::factory()->lawyer()->create();
    $other = User::factory()->lawyer()->create();
    $exam = Exam::factory()->for($owner, 'lawyer')->withQuestions()->create();

    $this->actingAs($other)
        ->put(route('admin.exams.update', $exam), examPayload())
        ->assertForbidden();
});

test('a lawyer cannot delete an exam that has attempts', function () {
    $lawyer = User::factory()->lawyer()->create();
    $exam = Exam::factory()->for($lawyer, 'lawyer')->withQuestions()->create();
    $trainee = User::factory()->trainee()->create();

    $this->actingAs($trainee)->post(route('trainee.exams.start', $exam));

    $this->actingAs($lawyer)
        ->delete(route('admin.exams.destroy', $exam))
        ->assertForbidden();

    $this->assertDatabaseHas('exams', ['id' => $exam->id]);
});
