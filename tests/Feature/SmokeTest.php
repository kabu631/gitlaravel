<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Gadget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/** The pages every visitor reaches must load, protect themselves and tell search engines the truth. */
class SmokeTest extends TestCase
{
    use RefreshDatabase;

    public static function pages(): array
    {
        return array_combine($p = [
            '/', '/products', '/search', '/search?q=phone', '/compare', '/price-tracker', '/tech-lab', '/custom-pc-build',
            '/reviews', '/news', '/guides', '/blog', '/brands', '/about', '/services', '/careers', '/contact',
            '/privacy-policy', '/terms-and-conditions', '/login', '/register', '/forgot-password', '/sitemap.xml', '/up',
        ], array_map(fn ($x) => [$x], $p));
    }

    #[DataProvider('pages')]
    public function test_public_page_loads(string $path): void
    {
        $this->get($path)->assertOk();
    }

    public function test_security_headers_are_sent(): void
    {
        $this->get('/')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeaderMissing('X-Powered-By');
    }

    public function test_missing_page_is_a_real_404_and_not_indexed(): void
    {
        $this->get('/no-such-page')->assertNotFound()->assertSee('noindex', false);
        $this->get('/products/no-such-device')->assertNotFound();
    }

    public function test_admin_panel_needs_a_login(): void
    {
        $this->get('/' . config('filament.path', 'secure-admin'))->assertRedirect();
    }

    public function test_account_pages_are_not_indexed(): void
    {
        $this->get('/login')->assertSee('noindex', false);
    }

    public function test_out_of_stock_product_is_reported_out_of_stock(): void
    {
        $gadget = $this->gadget(['out_of_stock_at' => '2026-11-23']);
        $this->get("/products/{$gadget->slug}")->assertOk()
            ->assertSee('schema.org/OutOfStock', false)->assertDontSee('schema.org/InStock', false);

        $gadget->update(['out_of_stock_at' => null]);
        $this->get("/products/{$gadget->slug}")->assertSee('schema.org/InStock', false);
    }

    public function test_repeated_logins_are_limited(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->post('/login', ['email' => "x{$i}@example.com", 'password' => 'wrong']);
        }
        $this->post('/login', ['email' => 'y@example.com', 'password' => 'wrong'])->assertTooManyRequests();
    }

    public function test_weak_passwords_are_refused(): void
    {
        $this->post('/register', ['name' => 'A', 'email' => 'a@example.com', 'password' => 'password', 'password_confirmation' => 'password'])
            ->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    private function gadget(array $extra = []): Gadget
    {
        return Gadget::create($extra + [
            'brand_id'       => Brand::create(['name' => 'Acme', 'slug' => 'acme'])->id,
            'category_id'    => Category::firstOrCreate(['slug' => 'mobile'], ['name' => 'Mobile'])->id,
            'name'           => 'Acme One',
            'slug'           => 'acme-one',
            'price'          => 1000,
            'description'    => '<p>Test</p>',
            'release_status' => Gadget::STATUS_AVAILABLE,
        ]);
    }
}
