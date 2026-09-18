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
        if (auth()->check() && auth()->user()->is_admin) {
            return back()->with('error', 'Administrators cannot order products.');
        }

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
                if ($existing->quantity + 1 > $variant->stock_quantity) {
                    return back()->with('error', "Only {$variant->stock_quantity} left in stock.");
                }
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
        $this->ownedItemOrFail($id)->delete();
        return back()->with('success', 'Item removed.');
    }

    public function update(Request $request, int $id)
    {
        $item = $this->ownedItemOrFail($id);
        $qty  = max(1, (int) $request->quantity);

        // Never let the cart hold more than what is actually in stock.
        if ($item->product_variant_id) {
            $stock = (int) optional($item->productVariant)->stock_quantity;
            if ($stock < 1) {
                return back()->with('error', 'That variant is out of stock.');
            }
            if ($qty > $stock) {
                $item->update(['quantity' => $stock]);
                return back()->with('warning', "Only {$stock} left in stock.");
            }
        }

        $item->update(['quantity' => $qty]);
        return back();
    }

    /** Resolve a cart item belonging to the current user/guest session, or 403. */
    private function ownedItemOrFail(int $id): CartItem
    {
        $item = CartItem::findOrFail($id);

        if (auth()->check()) {
            abort_if($item->user_id !== auth()->id(), 403);
        } else {
            abort_if($item->user_id !== null || $item->session_key !== session()->getId(), 403);
        }

        return $item;
    }
}
