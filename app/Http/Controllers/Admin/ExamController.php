<?php

namespace App\Http\Controllers\Admin;

use App\Enums\QuestionCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExamRequest;
use App\Http\Requests\Admin\UpdateExamRequest;
use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
use App\Models\User;
use App\Services\ExamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ExamController extends Controller
{
    public function __construct(private ExamService $exams) {}

    public function index(Request $request): Response
    {
        $lawyer = $request->user();
        abort_unless($lawyer instanceof User, 403);

        $exams = Exam::query()
            ->whereBelongsTo($lawyer, 'lawyer')
            ->withCount(['questions', 'attempts'])
            ->latest()
            ->orderByDesc('id')
            ->get()
            ->map(fn (Exam $exam): array => [
                'id' => $exam->id,
                'title' => $exam->title,
                'seconds_per_question' => $exam->seconds_per_question,
                'questions_count' => $exam->questions_count,
                'attempts_count' => $exam->attempts_count,
                'can_edit' => $exam->attempts_count === 0,
            ]);

        return Inertia::render('admin/exams/index', [
            'exams' => $exams,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Exam::class);

        return Inertia::render('admin/exams/create', [
            'categories' => $this->categories(),
        ]);
    }

    public function store(StoreExamRequest $request): RedirectResponse
    {
        $lawyer = $request->user();

        if (! $lawyer instanceof User) {
            abort(403);
        }

        $this->exams->create($lawyer, $request->examData());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'تم إنشاء الاختبار بنجاح.']);

        return to_route('admin.exams.index');
    }

    public function edit(Exam $exam): Response
    {
        $this->authorize('update', $exam);

        $exam->load('questions.options');

        return Inertia::render('admin/exams/edit', [
            'exam' => [
                'id' => $exam->id,
                'title' => $exam->title,
                'seconds_per_question' => $exam->seconds_per_question,
                'questions' => $exam->questions->map(function (Question $question): array {
                    return [
                        'scenario_text' => $question->scenario_text,
                        'category' => $question->category->value,
                        'explanation' => $question->explanation,
                        'options' => $question->options->map(function (Option $option): array {
                            return [
                                'text' => $option->text,
                                'is_correct' => $option->is_correct,
                            ];
                        })->values()->all(),
                    ];
                })->values()->all(),
            ],
            'categories' => $this->categories(),
        ]);
    }

    public function update(UpdateExamRequest $request, Exam $exam): RedirectResponse
    {
        $this->exams->update($exam, $request->examData());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'تم تحديث الاختبار.']);

        return to_route('admin.exams.index');
    }

    public function destroy(Exam $exam): RedirectResponse
    {
        $this->authorize('delete', $exam);

        $exam->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'تم حذف الاختبار.']);

        return to_route('admin.exams.index');
    }

    /**
     * @return list<array{value: string, label: string}>
     */
    private function categories(): array
    {
        return array_map(
            fn (QuestionCategory $category): array => [
                'value' => $category->value,
                'label' => $category->label(),
            ],
            QuestionCategory::cases(),
        );
    }
}
