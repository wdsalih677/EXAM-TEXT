<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trainee\SubmitAnswerRequest;
use App\Http\Requests\Trainee\TimeoutQuestionRequest;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\User;
use App\Services\ExamAttemptService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AttemptController extends Controller
{
    public function __construct(private ExamAttemptService $attempts) {}

    public function start(Request $request, Exam $exam): RedirectResponse
    {
        $this->authorize('start', $exam);

        $trainee = $request->user();
        abort_unless($trainee instanceof User, 403);

        $attempt = $this->attempts->start($trainee, $exam);

        return to_route('trainee.attempts.show', $attempt);
    }

    public function show(Attempt $attempt): Response|RedirectResponse
    {
        $this->authorize('view', $attempt);

        $attempt = $this->attempts->syncExpiredQuestion($attempt);

        if ($attempt->isCompleted()) {
            return to_route('trainee.attempts.result', $attempt);
        }

        return Inertia::render('trainee/exam/take', $this->attempts->takePageProps($attempt));
    }

    public function answer(SubmitAnswerRequest $request, Attempt $attempt): RedirectResponse
    {
        $this->attempts->saveAnswer($attempt, $request->integer('option_id'));

        return back();
    }

    public function next(Attempt $attempt): RedirectResponse
    {
        $this->authorize('answer', $attempt);

        $attempt = $this->attempts->moveToNextQuestion($attempt);

        if ($attempt->isCompleted()) {
            return to_route('trainee.attempts.result', $attempt);
        }

        return to_route('trainee.attempts.show', $attempt);
    }

    public function timeout(TimeoutQuestionRequest $request, Attempt $attempt): RedirectResponse
    {
        $this->attempts->timeoutCurrentQuestion($attempt, $request->integer('question_id'));

        return to_route('trainee.attempts.show', $attempt);
    }

    public function result(Attempt $attempt): Response
    {
        $this->authorize('viewResult', $attempt);

        return Inertia::render('trainee/exam/result', $this->attempts->resultPageProps($attempt));
    }
}
