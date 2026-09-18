<?php

namespace App\Http\Controllers;

use App\Exceptions\OutOfStockException;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    private function cartItems()
    {
        $query = auth()->check()
            ? CartItem::where('user_id', auth()->id())
            : CartItem::where('session_key', session()->getId());
        return $query->with('gadget')->get();
    }

    public function index()
    {
        if (auth()->check() && auth()->user()->is_admin) {
            return redirect()->route('home')->with('error', 'Administrators cannot order products.');
        }

        $items = $this->cartItems();
        if ($items->isEmpty()) return redirect()->route('cart.index')->with('warning', 'Your cart is empty.');
        $total = $items->sum(fn($i) => $i->subtotal);
        return Inertia::render('Orders/Checkout', ['items' => $items, 'total' => $total]);
    }

    public function store(Request $request)
    {
        if (auth()->check() && auth()->user()->is_admin) {
            return redirect()->route('home')->with('error', 'Administrators cannot order products.');
        }

        $request->validate([
            'first_name'      => 'required|string|max:50',
            'last_name'       => 'required|string|max:50',
            'email'           => 'required|email',
            'phone'           => 'required|string|max:20',
            'address'         => 'required|string',
            'payment_method'  => 'required|in:cod,esewa,khalti',
        ]);

        $items = $this->cartItems();
        if ($items->isEmpty()) return redirect()->route('cart.index');

        try {
            $order = DB::transaction(function () use ($request, $items) {
                $total = $items->sum(fn($i) => $i->subtotal);

                $order = Order::create([
                    'user_id'          => auth()->id(),
                    'first_name'       => $request->first_name,
                    'last_name'        => $request->last_name,
                    'email'            => $request->email,
                    'phone_number'     => $request->phone,
                    'shipping_address' => $request->address,
                    'payment_method'   => $request->payment_method,
                    'total_amount'     => $total,
                ]);

                foreach ($items as $item) {
                    // Re-check and reserve stock under a row lock so two shoppers
                    // can't both buy the last unit.
                    if ($item->product_variant_id) {
                        $variant = ProductVariant::whereKey($item->product_variant_id)->lockForUpdate()->first();

                        if (! $variant || $variant->stock_quantity < $item->quantity) {
                            $available = $variant?->stock_quantity ?? 0;
                            throw new OutOfStockException(
                                "\"{$item->gadget->name}\" only has {$available} left in stock. Please update your cart."
                            );
                        }

                        $variant->decrement('stock_quantity', $item->quantity);
                    }

                    OrderItem::create([
                        'order_id'           => $order->id,
                        'gadget_id'          => $item->gadget_id,
                        'product_variant_id' => $item->product_variant_id,
                        'price'              => $item->unit_price ?? $item->gadget->price,
                        'quantity'           => $item->quantity,
                        'variant_info'       => $item->variant_info,
                    ]);
                }

                $items->each->delete();

                return $order;
            });
        } catch (OutOfStockException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        // Let the guest who just placed this order view its confirmation page.
        $request->session()->push('placed_order_ids', $order->id);

        return redirect()->route('order.success', $order->id)->with('success', "Order #{$order->id} placed successfully!");
    }

    public function success(Request $request, int $orderId)
    {
        $order = Order::with('items.gadget')->findOrFail($orderId);

        // An order contains the customer's name, email, phone and address, so it
        // may only be viewed by the account that owns it, or by the guest who
        // just placed it (tracked in their own session).
        $ownsOrder = auth()->check() && $order->user_id === auth()->id();
        $placedInThisSession = in_array($order->id, (array) $request->session()->get('placed_order_ids', []), true);

        abort_unless($ownsOrder || $placedInThisSession, 403);

        return Inertia::render('Orders/Success', ['order' => $order]);
    }
}
