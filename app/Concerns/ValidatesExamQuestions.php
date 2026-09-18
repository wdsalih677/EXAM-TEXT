<?php

namespace App\Concerns;

use App\Enums\QuestionCategory;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

trait ValidatesExamQuestions
{
    /**
     * @return array<string, mixed>
     */
    protected function examRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'seconds_per_question' => ['required', 'integer', 'min:10', 'max:600'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.scenario_text' => ['required', 'string'],
            'questions.*.category' => ['required', Rule::enum(QuestionCategory::class)],
            'questions.*.explanation' => ['required', 'string'],
            'questions.*.options' => ['required', 'array', 'min:2'],
            'questions.*.options.*.text' => ['required', 'string'],
            'questions.*.options.*.is_correct' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function examAttributes(): array
    {
        return [
            'title' => 'عنوان الاختبار',
            'seconds_per_question' => 'مدة السؤال',
            'questions' => 'الأسئلة',
            'questions.*.scenario_text' => 'نص الحالة',
            'questions.*.category' => 'التصنيف',
            'questions.*.explanation' => 'التفسير',
            'questions.*.options' => 'الخيارات',
            'questions.*.options.*.text' => 'نص الخيار',
        ];
    }

    /**
     * @return list<callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $questions = $this->input('questions', []);

                if (! is_array($questions)) {
                    return;
                }

                foreach ($questions as $index => $question) {
                    if (! is_array($question) || ! isset($question['options']) || ! is_array($question['options'])) {
                        continue;
                    }

                    $correctCount = collect($question['options'])
                        ->filter(fn ($option): bool => is_array($option) && ($option['is_correct'] ?? false) === true)
                        ->count();

                    if ($correctCount !== 1) {
                        $validator->errors()->add(
                            "questions.{$index}.options",
                            'يجب تحديد إجابة صحيحة واحدة فقط لكل سؤال.',
                        );
                    }
                }
            },
        ];
    }

    /**
     * @return array{title: string, seconds_per_question: int, questions: list<array<string, mixed>>}
     */
    public function examData(): array
    {
        $questions = [];

        foreach (array_values($this->array('questions')) as $question) {
            if (! is_array($question)) {
                continue;
            }

            $questions[] = $question;
        }

        return [
            'title' => $this->string('title')->toString(),
            'seconds_per_question' => $this->integer('seconds_per_question'),
            'questions' => $questions,
        ];
    }
}
