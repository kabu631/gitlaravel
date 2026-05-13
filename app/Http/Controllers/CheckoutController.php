<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
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
        $items = $this->cartItems();
        if ($items->isEmpty()) return redirect()->route('cart.index')->with('warning', 'Your cart is empty.');
        $total = $items->sum(fn($i) => $i->subtotal);
        return Inertia::render('Orders/Checkout', ['items' => $items, 'total' => $total]);
    }

    public function store(Request $request)
    {
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

        return redirect()->route('order.success', $order->id)->with('success', "Order #{$order->id} placed successfully!");
    }

    public function success(int $orderId)
    {
        $order = Order::with('items.gadget')->findOrFail($orderId);
        return Inertia::render('Orders/Success', ['order' => $order]);
    }
}
