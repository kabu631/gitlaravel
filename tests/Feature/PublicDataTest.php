<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Gadget;
use App\Models\Review;
use App\Models\User;
use App\Models\UserComment;
use App\Support\PriceDrops;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** What visitors receive: no account details, and only real price drops. */
class PublicDataTest extends TestCase
{
    use RefreshDatabase;

    private function gadget(array $overrides = []): Gadget
    {
        return Gadget::create(array_merge([
            'brand_id'    => Brand::firstOrCreate(['slug' => 'acme'], ['name' => 'Acme'])->id,
            'category_id' => Category::firstOrCreate(['slug' => 'phones'], ['name' => 'Phones'])->id,
            'name'        => 'Acme One',
            'slug'        => 'acme-one',
            'price'       => 1000,
            'description' => 'A phone.',
        ], $overrides));
    }

    public function test_product_page_does_not_expose_the_email_of_people_who_comment(): void
    {
        $gadget = $this->gadget();
        $user = User::factory()->create(['name' => 'Sita', 'email' => 'private-reader@example.com']);
        UserComment::create(['gadget_id' => $gadget->id, 'user_id' => $user->id, 'rating' => 5, 'comment' => 'Good phone.']);

        $this->get('/products/acme-one')->assertOk()->assertSee('Sita')->assertDontSee('private-reader@example.com');
    }

    public function test_review_pages_do_not_expose_the_email_of_the_author(): void
    {
        $author = User::factory()->create(['email' => 'private-author@example.com']);
        Review::create([
            'gadget_id' => $this->gadget()->id, 'user_id' => $author->id, 'title' => 'Acme One Review', 'slug' => 'acme-one-review',
            'content' => '<p>Text.</p>', 'rating' => 8, 'verdict' => 'Good.', 'is_published' => true,
        ]);

        $this->get('/reviews')->assertOk()->assertSee('Acme One Review')->assertDontSee('private-author@example.com');
        $this->get('/reviews/acme-one-review')->assertOk()->assertDontSee('private-author@example.com');
    }

    public function test_price_drops_list_only_devices_really_on_sale_for_less(): void
    {
        $base = ['image' => 'gadgets/x.webp', 'old_price' => 1500, 'release_status' => Gadget::STATUS_AVAILABLE];
        $this->gadget($base + ['slug' => 'real']);
        $this->gadget(['slug' => 'estimate', 'price_is_estimate' => true] + $base);
        $this->gadget(['slug' => 'sold-out', 'out_of_stock_at' => '2026-11-23'] + $base);
        $this->gadget(['slug' => 'dearer', 'old_price' => 900] + $base);
        $this->gadget(['slug' => 'upcoming', 'release_status' => Gadget::STATUS_UPCOMING] + $base);

        $drops = PriceDrops::top(6);

        $this->assertSame(['real'], array_column($drops, 'slug'));
        $this->assertSame(-500.0, $drops[0]['price_change']);
    }

    public function test_admin_can_manage_variants_and_price_history_on_a_device(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $gadget = $this->gadget();

        $this->actingAs($admin)->get('/' . config('filament.path', 'secure-admin') . "/gadgets/{$gadget->id}/edit")
            ->assertOk()->assertSee('Product Variants (SKUs)')->assertSee('Price History');
    }
}
