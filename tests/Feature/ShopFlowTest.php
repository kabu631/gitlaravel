<?php

namespace Tests\Feature;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\Brand;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Gadget;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Closure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopFlowTest extends TestCase
{
    use RefreshDatabase;

    private function gadget(array $overrides = []): Gadget
    {
        $brand = Brand::create(['name' => 'Acme', 'slug' => 'acme']);
        $category = Category::create(['name' => 'Phones', 'slug' => 'phones']);

        return Gadget::create(array_merge([
            'brand_id'    => $brand->id,
            'category_id' => $category->id,
            'name'        => 'Acme One',
            'slug'        => 'acme-one',
            'price'       => 1000,
            'description' => 'A phone.',
        ], $overrides));
    }

    private function variant(Gadget $gadget, int $stock = 5): ProductVariant
    {
        return ProductVariant::create([
            'gadget_id'      => $gadget->id,
            'sku'            => 'ACME-1-' . $stock,
            'color'          => 'Black',
            'storage'        => '256GB',
            'price'          => 1000,
            'stock_quantity' => $stock,
            'is_active'      => true,
        ]);
    }

    private function cartItemFor(User $user, Gadget $gadget, ?ProductVariant $variant = null, int $qty = 1): CartItem
    {
        return CartItem::create([
            'user_id'            => $user->id,
            'gadget_id'          => $gadget->id,
            'product_variant_id' => $variant?->id,
            'quantity'           => $qty,
            'unit_price'         => 1000,
        ]);
    }

    private function checkoutPayload(): array
    {
        return [
            'first_name'     => 'Ram',
            'last_name'      => 'Shrestha',
            'email'          => 'ram@example.com',
            'phone'          => '9800000000',
            'address'        => 'Kathmandu',
            'payment_method' => 'cod',
        ];
    }

    public function test_guest_can_add_a_variant_to_the_cart(): void
    {
        $gadget = $this->gadget();
        $variant = $this->variant($gadget);

        $this->post("/cart/{$gadget->slug}", ['product_variant_id' => $variant->id])
            ->assertRedirect(route('cart.index'));

        $this->assertDatabaseCount('cart_items', 1);
    }

    public function test_a_user_cannot_change_another_users_cart_item(): void
    {
        $gadget = $this->gadget();
        $owner = User::factory()->create();
        $attacker = User::factory()->create();
        $item = $this->cartItemFor($owner, $gadget);

        $this->actingAs($attacker)
            ->patch("/cart/{$item->id}", ['quantity' => 99])
            ->assertForbidden();

        $this->assertSame(1, $item->fresh()->quantity);
    }

    public function test_a_user_cannot_delete_another_users_cart_item(): void
    {
        $gadget = $this->gadget();
        $owner = User::factory()->create();
        $attacker = User::factory()->create();
        $item = $this->cartItemFor($owner, $gadget);

        $this->actingAs($attacker)
            ->delete("/cart/{$item->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('cart_items', ['id' => $item->id]);
    }

    public function test_cart_quantity_is_capped_at_available_stock(): void
    {
        $gadget = $this->gadget();
        $variant = $this->variant($gadget, 3);
        $user = User::factory()->create();
        $item = $this->cartItemFor($user, $gadget, $variant);

        $this->actingAs($user)->patch("/cart/{$item->id}", ['quantity' => 50]);

        $this->assertSame(3, $item->fresh()->quantity);
    }

    public function test_checkout_decrements_stock_and_clears_the_cart(): void
    {
        $gadget = $this->gadget();
        $variant = $this->variant($gadget, 5);
        $user = User::factory()->create();
        $this->cartItemFor($user, $gadget, $variant, 2);

        $this->actingAs($user)
            ->post('/checkout', $this->checkoutPayload())
            ->assertRedirect();

        $this->assertSame(3, $variant->fresh()->stock_quantity);
        $this->assertDatabaseCount('cart_items', 0);
        $this->assertDatabaseHas('orders', ['email' => 'ram@example.com']);
    }

    public function test_checkout_is_rejected_when_stock_ran_out(): void
    {
        $gadget = $this->gadget();
        $variant = $this->variant($gadget, 1);
        $user = User::factory()->create();
        $this->cartItemFor($user, $gadget, $variant, 4);

        $this->actingAs($user)
            ->post('/checkout', $this->checkoutPayload())
            ->assertRedirect(route('cart.index'));

        // Nothing was ordered, reserved, or cleared.
        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(1, $variant->fresh()->stock_quantity);
        $this->assertDatabaseCount('cart_items', 1);
    }

    public function test_a_stranger_cannot_view_someone_elses_order_confirmation(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();

        $order = Order::create([
            'user_id'          => $owner->id,
            'first_name'       => 'Ram',
            'last_name'        => 'Shrestha',
            'email'            => 'ram@example.com',
            'phone_number'     => '9800000000',
            'shipping_address' => 'Kathmandu',
            'payment_method'   => 'cod',
            'total_amount'     => 1000,
        ]);

        $this->actingAs($stranger)->get("/order/success/{$order->id}")->assertForbidden();
        $this->actingAs($owner)->get("/order/success/{$order->id}")->assertOk();
    }

    /**
     * The array session driver used in tests issues a fresh id per request and
     * won't adopt a supplied one, so drive the merge step directly rather than
     * through a two-request login flow.
     */
    private function mergeGuestCart(string $guestSessionKey, int $userId): void
    {
        $controller = new AuthenticatedSessionController();

        Closure::bind(
            fn () => $this->mergeGuestCart($guestSessionKey, $userId),
            $controller,
            AuthenticatedSessionController::class
        )();
    }

    public function test_guest_cart_is_claimed_by_the_user_on_login(): void
    {
        $gadget = $this->gadget();
        $variant = $this->variant($gadget);
        $user = User::factory()->create();

        CartItem::create([
            'session_key'        => 'guest-session-abc',
            'gadget_id'          => $gadget->id,
            'product_variant_id' => $variant->id,
            'quantity'           => 2,
            'unit_price'         => 1000,
        ]);

        $this->mergeGuestCart('guest-session-abc', $user->id);

        $this->assertDatabaseCount('cart_items', 1);
        $this->assertDatabaseHas('cart_items', [
            'user_id'     => $user->id,
            'session_key' => null,
            'quantity'    => 2,
        ]);
    }

    public function test_guest_cart_quantities_fold_into_an_existing_account_item(): void
    {
        $gadget = $this->gadget();
        $variant = $this->variant($gadget);
        $user = User::factory()->create();

        $this->cartItemFor($user, $gadget, $variant, 1);
        CartItem::create([
            'session_key'        => 'guest-session-abc',
            'gadget_id'          => $gadget->id,
            'product_variant_id' => $variant->id,
            'quantity'           => 3,
            'unit_price'         => 1000,
        ]);

        $this->mergeGuestCart('guest-session-abc', $user->id);

        // The two rows collapse into one rather than duplicating the line item.
        $this->assertDatabaseCount('cart_items', 1);
        $this->assertDatabaseHas('cart_items', ['user_id' => $user->id, 'quantity' => 4]);
    }
}
