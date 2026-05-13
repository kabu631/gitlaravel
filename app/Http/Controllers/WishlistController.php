<?php

namespace App\Http\Controllers;

use App\Models\Gadget;
use Inertia\Inertia;

class WishlistController extends Controller
{
    public function index()
    {
        $gadgets = auth()->user()->wishlist()->with('brand')->get();
        return Inertia::render('Wishlist/Index', ['gadgets' => $gadgets]);
    }

    public function toggle(string $slug)
    {
        $gadget = Gadget::where('slug', $slug)->firstOrFail();
        auth()->user()->wishlist()->toggle($gadget->id);
        return back();
    }
}
