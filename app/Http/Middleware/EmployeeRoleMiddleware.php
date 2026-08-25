<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmployeeRoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole('employee')) {
            abort(403, 'Unauthorized access.');
        }

        $employee = $user->employee;
        if (!$employee || !$employee->is_active) {
            abort(403, 'Your account is inactive.');
        }

        // Check if employee has the required role
        if (!in_array($employee->role, $roles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
