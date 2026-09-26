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
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Admin-managed and slug-change redirects (SEO → URL Redirects) are resolved only when a URL would 404.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, \Illuminate\Http\Request $request) {
            if (! $request->isMethod('GET') || ! \Illuminate\Support\Facades\Schema::hasTable('seo_redirects')) {
                return null;
            }

            $redirect = \App\Models\SeoRedirect::findFor($request->getPathInfo());
            if (! $redirect) {
                return null;
            }

            $redirect->increment('hits', 1, ['last_hit_at' => now()]);
            $query = $request->getQueryString();

            return redirect()->to($redirect->to_path . ($query ? "?{$query}" : ''), $redirect->status_code);
        });
    })->create();
