<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // Auto-clean stale public/hot file if Vite dev server is offline to prevent white screen
        if (file_exists(public_path('hot'))) {
            $hotUrl = trim(@file_get_contents(public_path('hot')));
            if ($hotUrl) {
                $port = parse_url($hotUrl, PHP_URL_PORT) ?: 5173;
                $conn = @fsockopen('127.0.0.1', $port, $errno, $errstr, 0.1);
                if (!is_resource($conn)) {
                    @unlink(public_path('hot'));
                } else {
                    fclose($conn);
                }
            }
        }
    }
}
