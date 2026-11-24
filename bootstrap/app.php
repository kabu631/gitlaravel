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
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

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

        // 403/404 for page visits use the site layout (menu, search); everything else falls back to the plain
        // resources/views/errors pages, which work even when the database is down.
        $exceptions->respond(function (\Symfony\Component\HttpFoundation\Response $response, \Throwable $e, \Illuminate\Http\Request $request) {
            $status = $response->getStatusCode();
            $adminPath = trim((string) config('filament.path', 'secure-admin'), '/');
            if (! in_array($status, [403, 404], true) || ! $request->isMethod('GET') || $request->is($adminPath, $adminPath . '/*')
                || ($request->expectsJson() && ! $request->header('X-Inertia'))
                || preg_match('/\.[a-z0-9]{2,5}$/i', $request->path())) {
                return $response;
            }

            try {
                return \Inertia\Inertia::render('Error', [
                    'status' => $status,
                    'seo'    => \App\Support\Seo::make(['title' => $status === 404 ? 'Page not found' : 'No access', 'noindex' => true]),
                ])->toResponse($request)->setStatusCode($status);
            } catch (\Throwable) {
                return $response;
            }
        });
    })->create();
