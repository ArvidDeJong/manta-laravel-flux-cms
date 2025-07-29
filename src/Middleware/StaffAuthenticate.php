<?php

namespace Manta\FluxCMS\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class StaffAuthenticate extends Middleware
{
    /**
     * Handle an unauthenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $guards
     * @return void
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function redirectTo(Request $request, array $guards = []): ?string
    {
        if (! $request->expectsJson()) {
            return route('flux-cms.staff.login');
        }
        
        return null;
    }
}
