<?php

use App\Console\Commands\AppointmentMissedStatusUpdate;
use App\Console\Commands\DatabaseBackup;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'check.session' => \App\Http\Middleware\CheckSession::class,
        ]);
    })
    ->withCommands([
        DatabaseBackup::class,
        AppointmentMissedStatusUpdate::class,
    ])

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
