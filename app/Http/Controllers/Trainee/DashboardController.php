<?php

namespace App\Http\Controllers\Trainee;

use App\Enums\AttemptStatus;
use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $trainee = $request->user();
        abort_unless($trainee instanceof User, 403);

        $exams = Exam::query()
            ->has('questions')
            ->withCount('questions')
            ->with(['attempts' => fn ($query) => $query->where('trainee_id', $trainee->id)])
            ->latest()
            ->orderByDesc('id')
            ->get()
            ->map(function (Exam $exam): array {
                $attempt = $exam->attempts->first();

                return [
                    'id' => $exam->id,
                    'title' => $exam->title,
                    'questions_count' => $exam->questions_count,
                    'seconds_per_question' => $exam->seconds_per_question,
                    'attempt' => $attempt === null ? null : [
                        'id' => $attempt->id,
                        'status' => $attempt->status->value,
                        'score' => $attempt->score,
                    ],
                ];
            });

        $completedCount = Attempt::query()
            ->where('trainee_id', $trainee->id)
            ->where('status', AttemptStatus::Completed)
            ->count();

        return Inertia::render('trainee/dashboard', [
            'exams' => $exams,
            'completed_count' => $completedCount,
        ]);
    }
}
