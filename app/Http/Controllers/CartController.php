<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Gadget;
use App\Models\GadgetVariant;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    /** Legacy price lookup for old attribute-based variant system */
    private function getVariantPrice(Gadget $gadget, array $variantInfo): float
    {
        foreach ($variantInfo as $type => $value) {
            $variant = GadgetVariant::where('gadget_id', $gadget->id)
                ->where('variant_type', $type)->where('value', $value)->first();
            if ($variant && $variant->price !== null) {
                return (float) $variant->price;
            }
        }
        return (float) $gadget->price;
    }

    private function cartQuery()
    {
        if (auth()->check()) {
            return CartItem::where('user_id', auth()->id());
        }
        return CartItem::where('session_key', session()->getId());
    }

    public function index()
    {
        $items = $this->cartQuery()
            ->with(['gadget.brand', 'productVariant'])
            ->get();
        $total = $items->sum(fn($i) => $i->subtotal);
        return Inertia::render('Cart/Index', ['items' => $items, 'total' => $total]);
    }

    public function add(Request $request, string $slug)
    {
        $gadget    = Gadget::where('slug', $slug)->firstOrFail();
        $variantId = $request->input('product_variant_id');

        // ── NEW SKU-based variant system ──────────────────────────────────
        if ($variantId) {
            $variant = ProductVariant::where('id', $variantId)
                ->where('gadget_id', $gadget->id)
                ->where('is_active', true)
                ->firstOrFail();

            if ($variant->stock_quantity < 1) {
                return back()->with('error', 'Sorry, this variant is currently out of stock.');
            }

            $unitPrice   = (float) ($variant->discounted_price ?? $variant->price);
            $variantInfo = array_filter([
                'color'   => $variant->color,
                'ram'     => $variant->ram,
                'storage' => $variant->storage,
                'size'    => $variant->size,
            ]);

            $existing = $this->cartQuery()
                ->where('gadget_id', $gadget->id)
                ->where('product_variant_id', $variantId)
                ->first();

            if ($existing) {
                $existing->increment('quantity');
            } else {
                CartItem::create([
                    'user_id'            => auth()->id(),
                    'session_key'        => auth()->check() ? null : session()->getId(),
                    'gadget_id'          => $gadget->id,
                    'product_variant_id' => $variantId,
                    'variant_info'       => $variantInfo ?: null,
                    'unit_price'         => $unitPrice,
                ]);
            }

            return redirect()->route('cart.index')->with('success', "{$gadget->name} added to cart.");
        }

        // ── Legacy attribute-based variant system (backward compat) ───────
        $variantInfo = [];
        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'variant_') && $value) {
                $variantInfo[substr($key, 8)] = $value;
            }
        }
        $unitPrice = $this->getVariantPrice($gadget, $variantInfo);

        $existing = $this->cartQuery()
            ->where('gadget_id', $gadget->id)
            ->whereNull('product_variant_id')
            ->first();

        if ($existing) {
            $existing->increment('quantity');
        } else {
            CartItem::create([
                'user_id'            => auth()->id(),
                'session_key'        => auth()->check() ? null : session()->getId(),
                'gadget_id'          => $gadget->id,
                'product_variant_id' => null,
                'variant_info'       => $variantInfo ?: null,
                'unit_price'         => $unitPrice,
            ]);
        }

        return redirect()->route('cart.index')->with('success', "{$gadget->name} added to cart.");
    }

    public function remove(int $id)
    {
        $item = CartItem::findOrFail($id);
        if (auth()->check() && $item->user_id !== auth()->id()) abort(403);
        if (!auth()->check() && $item->session_key !== session()->getId()) abort(403);
        $item->delete();
        return back()->with('success', 'Item removed.');
    }

    public function update(Request $request, int $id)
    {
        $item = CartItem::findOrFail($id);
        $qty  = max(1, (int) $request->quantity);
        $item->update(['quantity' => $qty]);
        return back();
    }
}
