<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Popup;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
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
            'navCategories' => fn () => Category::orderBy('id')->get(['id', 'name', 'slug']),
            'headerMenu'   => fn () => MenuItem::tree(),
            'activePopup'  => fn () => Popup::live()->first(['id', 'title', 'body', 'image', 'badge', 'btn_text', 'btn_url', 'delay_seconds', 'frequency', 'updated_at']),
            'adminUrl' => '/' . ltrim(config('filament.path', 'secure-admin'), '/'),
            'flash'    => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info'    => fn () => $request->session()->get('info'),
            ],
        ];
    }
}
