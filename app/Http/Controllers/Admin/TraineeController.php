<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTraineeRequest;
use App\Http\Requests\Admin\UpdateTraineeRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TraineeController extends Controller
{
    public function index(): Response
    {
        $trainees = User::query()
            ->where('role', UserRole::Trainee)
            ->latest()
            ->orderByDesc('id')
            ->get()
            ->map(fn (User $trainee): array => [
                'id' => $trainee->id,
                'name' => $trainee->name,
                'email' => $trainee->email,
                'role_label' => $trainee->role->label(),
                'created_at' => $trainee->created_at?->toDateString(),
            ]);

        return Inertia::render('admin/trainees/index', [
            'trainees' => $trainees,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/trainees/create');
    }

    public function store(StoreTraineeRequest $request): RedirectResponse
    {
        User::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'role' => UserRole::Trainee,
            'email_verified_at' => now(),
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'تم إضافة المتدرب بنجاح.']);

        return to_route('admin.trainees.index');
    }

    public function edit(User $trainee): Response
    {
        abort_unless($trainee->isTrainee(), 404);

        return Inertia::render('admin/trainees/edit', [
            'trainee' => [
                'id' => $trainee->id,
                'name' => $trainee->name,
                'email' => $trainee->email,
            ],
        ]);
    }

    public function update(UpdateTraineeRequest $request, User $trainee): RedirectResponse
    {
        abort_unless($trainee->isTrainee(), 404);

        $data = $request->safe()->only(['name', 'email']);

        if (filled($request->validated('password'))) {
            $data['password'] = $request->validated('password');
        }

        $trainee->update($data);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'تم تحديث بيانات المتدرب.']);

        return to_route('admin.trainees.index');
    }

    public function destroy(User $trainee): RedirectResponse
    {
        abort_unless(request()->user()?->isLawyer() && $trainee->isTrainee(), 403);

        $trainee->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'تم حذف المتدرب.']);

        return to_route('admin.trainees.index');
    }
}
