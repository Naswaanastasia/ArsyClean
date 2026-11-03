<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Meredirect user yang SUDAH login ketika mencoba mengakses route 'guest' (seperti /login)
        $middleware->redirectUsersTo(function (Request $request) {
            $user = $request->user();

            if ($user) {
                if ($user->role === 'admin') {
                    return route('admin.dashboard');
                } elseif ($user->role === 'owner') {
                    return route('owner.dashboard');
                } elseif ($user->role === 'pelanggan') {
                    return route('pelanggan.dashboard');
                }
            }

            return '/';
        });

        $middleware->validateCsrfTokens(except: [
<<<<<<< HEAD
=======
    'midtrans/notification',
>>>>>>> 9da5eb4 (Update fitur pembayaran QRIS ArsyClean)
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();