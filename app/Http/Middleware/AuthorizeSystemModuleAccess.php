<?php

namespace App\Http\Middleware;

use App\Models\SystemModule;
use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeSystemModuleAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If not logged in, pass to auth middleware
        if (! $user) {
            return $next($request);
        }

        // Super Administrators bypass all permission restrictions
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $filamentPath = config('filament.path', 'secure-admin');
        $currentPath = trim($request->path(), '/');

        // Allow panel root (Dashboard), login, logout, and livewire internal calls
        if (
            $currentPath === $filamentPath ||
            $currentPath === "{$filamentPath}/login" ||
            $currentPath === "{$filamentPath}/logout" ||
            str_starts_with($currentPath, 'livewire/')
        ) {
            return $next($request);
        }

        // Find all active modules with configured routes
        $modules = SystemModule::whereNotNull('route')
            ->where('status', 'active')
            ->get();

        $matchingModule = null;
        $longestMatchLength = 0;

        foreach ($modules as $module) {
            $resolved = $module->getResolvedUrl();
            if (! $resolved) {
                continue;
            }

            $modulePath = trim(parse_url($resolved, PHP_URL_PATH) ?? '', '/');
            if (empty($modulePath)) {
                continue;
            }

            if ($currentPath === $modulePath || str_starts_with($currentPath, "{$modulePath}/")) {
                if (strlen($modulePath) > $longestMatchLength) {
                    $matchingModule = $module;
                    $longestMatchLength = strlen($modulePath);
                }
            }
        }

        // If this route belongs to a tracked module, enforce role permissions
        if ($matchingModule) {
            if (! $user->hasPermissionToModule($matchingModule)) {
                if ($request->expectsJson() || $request->header('X-Livewire')) {
                    abort(403, "You do not have permission to access the '{$matchingModule->name}' module.");
                }

                Notification::make()
                    ->title('Access Restricted')
                    ->body("You do not have permission to access the '{$matchingModule->name}' module.")
                    ->danger()
                    ->send();

                return redirect("/{$filamentPath}");
            }
        }

        return $next($request);
    }
}
