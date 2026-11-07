<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Added by EnvKit so shared (public) URLs keep the https scheme and
        // public host. Trusts loopback only (the tunnel/Octane proxy); remove to opt out.
        $middleware->trustProxies(at: ['127.0.0.1', '::1', '::ffff:127.0.0.1']);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'employee' => \App\Http\Middleware\EmployeeMiddleware::class,
            'employee.role' => \App\Http\Middleware\EmployeeRoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
