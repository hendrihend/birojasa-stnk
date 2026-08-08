<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; 
use Illuminate\Http\Exceptions\PostTooLargeException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        // Tangkap error jika ukuran file melebihi batas server
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            return back()->withErrors(['file_dokumen' => 'Gagal! Ukuran file secara keseluruhan terlalu besar untuk diproses oleh server.']);
        });

    })->create();

