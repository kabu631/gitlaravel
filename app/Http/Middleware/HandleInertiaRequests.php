<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Popup;
use App\Models\SiteSetting;
use App\Support\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth'     => ['user' => $request->user()],
            'siteName' => config('app.name'),
            'baseUrl'  => config('app.url'),
            'settings' => fn () => SiteSetting::publicSettings(),
            // Only categories that have products; empty ones would be dead-end menu links.
            'navCategories' => fn () => Cache::remember('nav-categories:v1', 600, fn () => Category::whereHas('gadgets')->orderBy('id')->get(['id', 'name', 'slug'])->toArray()),
            'headerMenu'   => fn () => MenuItem::tree(),
            'activePopup'  => fn () => Popup::live()->first(['id', 'title', 'body', 'image', 'badge', 'btn_text', 'btn_url', 'delay_seconds', 'frequency', 'updated_at']),
            'seo'          => fn () => Seo::make(),
            // Only admins get the panel address; everyone else shouldn't see it in the page source.
            'adminUrl' => fn () => $request->user()?->is_admin ? '/' . ltrim(config('filament.path', 'secure-admin'), '/') : null,
            'flash'    => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info'    => fn () => $request->session()->get('info'),
            ],
        ];
    }
}
