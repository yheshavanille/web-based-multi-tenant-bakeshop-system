<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmployeeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole('employee')) {
            abort(403, 'Unauthorized access.');
        }

        // Check if employee is active
        $employee = $user->employee;
        if (!$employee || !$employee->is_active) {
            Auth::logout();
            return redirect()->route('livewire.auth.login')->with('error', 'Your account is inactive.');
        }

        return $next($request);
    }
}
