<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user instanceof User && $user->isLawyer()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('trainee.dashboard');
    }
}
