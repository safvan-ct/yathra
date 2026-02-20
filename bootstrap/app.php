<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectUsersTo(function ($request) {
            if (Auth::guard('operator')->check()) {
                return route('operator.dashboard');
            }

            if (Auth::guard('web')->check()) {
                return route('backend.dashboard');
            }

            return route('home');
        });

        $middleware->redirectGuestsTo(function ($request) {

            if ($request->is('operator/*')) {
                return route('operator.login');
            }

            if ($request->is('backend/*')) {
                return route('backend.login');
            }

            return route('backend.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
