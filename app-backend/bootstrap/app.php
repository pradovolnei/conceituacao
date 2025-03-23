<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Core\Middlewares\AuthorizeAdministrator;
use App\Core\Console\Commands\Swagger;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../app/Core/Routes/Web.php',
        api: __DIR__.'/../app/Core/Routes/Api.php',
        commands: __DIR__.'/../app/Core/Routes/Console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'guard.administrator' => AuthorizeAdministrator::class
        ]);
    })
    ->withCommands([
        Swagger::class
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
