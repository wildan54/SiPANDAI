<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // daftar middleware untuk route web
        $middleware->web([
            \App\Http\Middleware\UpdateLastActive::class,
        ]);

        // kalau mau untuk api juga bisa:
        // $middleware->api([
        //     \App\Http\Middleware\UpdateLastActive::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();