<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AttemptStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Models\Exam;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $completedAttempts = Attempt::query()->where('status', AttemptStatus::Completed);

        return Inertia::render('admin/dashboard', [
            'stats' => [
                'trainees_count' => User::query()->where('role', UserRole::Trainee)->count(),
                'exams_count' => Exam::query()->count(),
                'completed_attempts_count' => (clone $completedAttempts)->count(),
                'average_score' => round((float) (clone $completedAttempts)->avg('score') ?: 0, 1),
            ],
        ]);
    }
}
