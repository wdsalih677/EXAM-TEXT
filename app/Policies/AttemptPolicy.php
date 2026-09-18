<?php

namespace App\Policies;

use App\Models\Attempt;
use App\Models\User;

class AttemptPolicy
{
    public function view(User $user, Attempt $attempt): bool
    {
        return $user->isTrainee() && $attempt->belongsToTrainee($user);
    }

    public function answer(User $user, Attempt $attempt): bool
    {
        return $this->view($user, $attempt) && $attempt->isInProgress();
    }

    public function viewResult(User $user, Attempt $attempt): bool
    {
        return $this->view($user, $attempt) && $attempt->isCompleted();
    }
}
