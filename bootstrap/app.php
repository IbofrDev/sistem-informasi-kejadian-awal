<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Pastikan baris ini ada
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\CheckAdminRole::class,
        ]);

        // INI BAGIAN YANG PALING PENTING
        // Terapkan middleware CORS ke grup 'api'
        $middleware->api(append: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);

        $middleware->redirectGuestsTo('/');
        $middleware->redirectUsersTo(function () {
            if (Auth::user()->role === 'admin') {
                return '/admin/dashboard';
            }
            return '/dashboard';
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
