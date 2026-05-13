<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function edit(Request $request): Response
    {
        $user = $request->user();

        $orders = $user->orders()
            ->with(['items.gadget.brand'])
            ->latest()
            ->get()
            ->map(fn($o) => [
                'id'           => $o->id,
                'status'       => $o->status,
                'total_amount' => (float) $o->total_amount,
                'is_paid'      => $o->is_paid,
                'created_at'   => $o->created_at->toDateString(),
                'items'        => $o->items->map(fn($i) => [
                    'id'       => $i->id,
                    'name'     => $i->gadget->name ?? 'Product',
                    'brand'    => $i->gadget->brand?->name,
                    'image'    => $i->gadget->image,
                    'slug'     => $i->gadget->slug,
                    'price'    => (float) $i->price,
                    'quantity' => $i->quantity,
                ]),
            ]);

        $reviews = $user->reviews()
            ->with('gadget.brand')
            ->latest()
            ->get()
            ->map(fn($r) => [
                'id'          => $r->id,
                'title'       => $r->title,
                'slug'        => $r->slug,
                'rating'      => (float) $r->rating,
                'verdict'     => $r->verdict,
                'gadget_name' => $r->gadget?->name,
                'gadget_slug' => $r->gadget?->slug,
                'gadget_image'=> $r->gadget?->image,
                'created_at'  => $r->created_at->toDateString(),
            ]);

        $wishlist = $user->wishlist()
            ->with('brand')
            ->get()
            ->map(fn($g) => [
                'id'    => $g->id,
                'name'  => $g->name,
                'slug'  => $g->slug,
                'image' => $g->image,
                'price' => (float) $g->price,
                'brand' => $g->brand?->name,
            ]);

        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status'          => session('status'),
            'orders'          => $orders,
            'reviews'         => $reviews,
            'wishlist'        => $wishlist,
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'current_password']]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
