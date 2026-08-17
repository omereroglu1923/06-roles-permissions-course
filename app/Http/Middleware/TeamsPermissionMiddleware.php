<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TeamsPermissionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! empty($user = Auth::user()) && ! empty($user->current_team_id)) {
            setPermissionsTeamId($user->current_team_id);
            $user->unsetRelation('roles')->unsetRelation('permissions');
        }

        return $next($request);
    }
}
