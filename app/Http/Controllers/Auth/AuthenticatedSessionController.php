<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\CartItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Captured before regeneration, which issues a new session id.
        $guestSessionKey = $request->session()->getId();

        $request->authenticate();

        $request->session()->regenerate();

        $this->mergeGuestCart($guestSessionKey, Auth::id());

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Carry a guest's cart over to their account on login, so items added
     * before signing in are not silently lost.
     */
    protected function mergeGuestCart(string $guestSessionKey, int $userId): void
    {
        $guestItems = CartItem::where('session_key', $guestSessionKey)->whereNull('user_id')->get();

        if ($guestItems->isEmpty()) {
            return;
        }

        foreach ($guestItems as $item) {
            $existing = CartItem::where('user_id', $userId)
                ->where('gadget_id', $item->gadget_id)
                ->where('product_variant_id', $item->product_variant_id)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $item->quantity);
                $item->delete();
                continue;
            }

            $item->update(['user_id' => $userId, 'session_key' => null]);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
