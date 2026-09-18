<?php

namespace App\Policies;

use App\Models\Exam;
use App\Models\User;

class ExamPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isLawyer() || $user->isTrainee();
    }

    public function view(User $user, Exam $exam): bool
    {
        if ($user->isLawyer()) {
            return $exam->lawyer_id === $user->id;
        }

        return $user->isTrainee() && $exam->questions()->exists();
    }

    public function create(User $user): bool
    {
        return $user->isLawyer();
    }

    public function update(User $user, Exam $exam): bool
    {
        return $user->isLawyer()
            && $exam->lawyer_id === $user->id
            && ! $exam->hasAttempts();
    }

    public function delete(User $user, Exam $exam): bool
    {
        return $this->update($user, $exam);
    }

    public function start(User $user, Exam $exam): bool
    {
        return $user->isTrainee() && $exam->questions()->exists();
    }
}
