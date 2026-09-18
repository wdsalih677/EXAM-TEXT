<?php

namespace App\Http\Responses;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  Request  $request
     */
    public function toResponse($request): RedirectResponse
    {
        $user = $request->user();

        $route = $user instanceof User && $user->isLawyer()
            ? 'admin.dashboard'
            : 'trainee.dashboard';

        return redirect()->intended(route($route));
    }
}
