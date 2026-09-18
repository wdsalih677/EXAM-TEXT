<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\TraineeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Trainee\AttemptController;
use App\Http\Controllers\Trainee\DashboardController as TraineeDashboardController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
});

Route::middleware(['auth', 'role:lawyer'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('trainees', TraineeController::class)->except(['show']);
    Route::resource('exams', ExamController::class)->except(['show']);
});

Route::middleware(['auth', 'role:trainee'])->prefix('trainee')->name('trainee.')->group(function () {
    Route::get('/', [TraineeDashboardController::class, 'index'])->name('dashboard');
    Route::post('exams/{exam}/start', [AttemptController::class, 'start'])->name('exams.start');
    Route::get('attempts/{attempt}', [AttemptController::class, 'show'])->name('attempts.show');
    Route::post('attempts/{attempt}/answer', [AttemptController::class, 'answer'])->name('attempts.answer');
    Route::post('attempts/{attempt}/next', [AttemptController::class, 'next'])->name('attempts.next');
    Route::post('attempts/{attempt}/timeout', [AttemptController::class, 'timeout'])->name('attempts.timeout');
    Route::get('attempts/{attempt}/result', [AttemptController::class, 'result'])->name('attempts.result');
});

require __DIR__.'/settings.php';
