<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\MobileOwnerMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(web: __DIR__.'/../routes/web.php', api: __DIR__.'/../routes/api.php', commands: __DIR__.'/../routes/console.php', health: '/up')
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['role' => RoleMiddleware::class, 'mobile.owner' => MobileOwnerMiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) { $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e) { if ($e->getStatusCode() === 403) return response()->view('errors.403', [], 403); if ($e->getStatusCode() === 404) return response()->view('errors.404', [], 404); }); })
    ->create();
