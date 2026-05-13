<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateFromDjango extends Command
{
    protected $signature = 'migrate:from-django';
    protected $description = 'Copy data from Django git_infosys DB into Laravel git_infosys_v2';

    private $src;

    public function handle(): int
    {
        $this->src = DB::connection('django');

        $this->info('Starting migration from git_infosys → git_infosys_v2...');

        $this->migrateUsers();
        $this->migrateBrands();
        $this->migrateCategories();
        $this->migrateGadgets();
        $this->migrateGadgetImages();
        $this->migrateVariants();
        $this->migrateSpecs();
        $this->migratePriceHistory();
        $this->migrateNewsArticles();
        $this->migrateTechGuides();
        $this->migrateOrders();
        $this->migrateWishlists();
        $this->migrateReviews();

        $this->info('Migration complete!');
        return 0;
    }

    private function migrateUsers(): void
    {
        $this->line('→ Users');
        $rows = $this->src->table('auth_user')->get();
        foreach ($rows as $r) {
            DB::table('users')->updateOrInsert(['email' => $r->email], [
                'name'       => trim($r->first_name . ' ' . $r->last_name) ?: $r->username,
                'email'      => $r->email,
                'password'   => $r->password,
                'is_admin'   => $r->is_staff,
                'created_at' => $r->date_joined,
                'updated_at' => $r->date_joined,
            ]);
        }
        $this->line('  ' . count($rows) . ' users');
    }

    private function migrateBrands(): void
    {
        $this->line('→ Brands');
        $rows = $this->src->table('gadgets_brand')->get();
        foreach ($rows as $r) {
            DB::table('brands')->updateOrInsert(['slug' => $r->slug], [
                'name'       => $r->name,
                'slug'       => $r->slug,
                'logo'       => $r->logo ?: null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->line('  ' . count($rows) . ' brands');
    }

    private function migrateCategories(): void
    {
        $this->line('→ Categories');
        $rows = $this->src->table('gadgets_category')->get();
        foreach ($rows as $r) {
            DB::table('categories')->updateOrInsert(['slug' => $r->slug], [
                'name'       => $r->name,
                'slug'       => $r->slug,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->line('  ' . count($rows) . ' categories');
    }

    private function migrateGadgets(): void
    {
        $this->line('→ Gadgets');
        $rows = $this->src->table('gadgets_gadget')->get();
        $brandMap    = DB::table('brands')->pluck('id', 'slug');
        $categoryMap = DB::table('categories')->pluck('id', 'slug');

        // pre-load brand/category slugs from Django
        $djangoBrands     = $this->src->table('gadgets_brand')->pluck('slug', 'id');
        $djangoCategories = $this->src->table('gadgets_category')->pluck('slug', 'id');

        foreach ($rows as $r) {
            $brandId    = isset($djangoBrands[$r->brand_id]) ? ($brandMap[$djangoBrands[$r->brand_id]] ?? null) : null;
            $categoryId = $r->new_category_id && isset($djangoCategories[$r->new_category_id])
                ? ($categoryMap[$djangoCategories[$r->new_category_id]] ?? null)
                : null;

            DB::table('gadgets')->updateOrInsert(['slug' => $r->slug], [
                'name'            => $r->name,
                'slug'            => $r->slug,
                'brand_id'        => $brandId,
                'category_id'     => $categoryId,
                'price'           => $r->price,
                'old_price'       => $r->old_price ?? null,
                'image'           => $r->image ?: null,
                'description'     => $r->description ?? null,
                'is_featured'     => $r->is_featured ?? false,
                'is_trending'     => $r->is_trending ?? false,
                'views_count'     => $r->views_count ?? 0,
                'accessory_type'  => $r->accessory_type ?: null,
                'release_date'    => $r->release_date ?? null,
                'sketchfab_embed' => $r->sketchfab_embed ?? null,
                'created_at'      => $r->created_at ?? now(),
                'updated_at'      => $r->updated_at ?? now(),
            ]);
        }
        $this->line('  ' . count($rows) . ' gadgets');
    }

    private function migrateGadgetImages(): void
    {
        $this->line('→ Gadget Images');
        $rows = $this->src->table('gadgets_gadgetimage')->get();
        $gadgetMap = DB::table('gadgets')->pluck('id', 'slug');
        $djangoGadgets = $this->src->table('gadgets_gadget')->pluck('slug', 'id');

        DB::table('gadget_images')->truncate();
        foreach ($rows as $r) {
            $slug     = $djangoGadgets[$r->gadget_id] ?? null;
            $gadgetId = $slug ? ($gadgetMap[$slug] ?? null) : null;
            if (!$gadgetId) continue;
            DB::table('gadget_images')->insert([
                'gadget_id'  => $gadgetId,
                'image'      => $r->image,
                'alt_text'   => $r->alt_text ?? null,
                'order'      => $r->order ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->line('  ' . count($rows) . ' images');
    }

    private function migrateVariants(): void
    {
        $this->line('→ Variants');
        $rows = $this->src->table('gadgets_gadgetvariant')->get();
        $gadgetMap     = DB::table('gadgets')->pluck('id', 'slug');
        $djangoGadgets = $this->src->table('gadgets_gadget')->pluck('slug', 'id');

        DB::table('gadget_variants')->truncate();
        foreach ($rows as $r) {
            $slug     = $djangoGadgets[$r->gadget_id] ?? null;
            $gadgetId = $slug ? ($gadgetMap[$slug] ?? null) : null;
            if (!$gadgetId) continue;
            DB::table('gadget_variants')->insert([
                'gadget_id'    => $gadgetId,
                'variant_type' => $r->variant_type,
                'value'        => $r->value,
                'price'        => $r->price ?? null,
                'stock'        => $r->stock ?? 0,
                'is_available' => $r->is_available ?? true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
        $this->line('  ' . count($rows) . ' variants');
    }

    private function migrateSpecs(): void
    {
        $this->line('→ Specs');
        $rows = $this->src->table('gadgets_specsheet')->get();
        $gadgetMap     = DB::table('gadgets')->pluck('id', 'slug');
        $djangoGadgets = $this->src->table('gadgets_gadget')->pluck('slug', 'id');

        DB::table('spec_sheets')->truncate();
        foreach ($rows as $r) {
            $slug     = $djangoGadgets[$r->gadget_id] ?? null;
            $gadgetId = $slug ? ($gadgetMap[$slug] ?? null) : null;
            if (!$gadgetId) continue;
            DB::table('spec_sheets')->insert([
                'gadget_id'    => $gadgetId,
                'display'      => $r->display ?? null,
                'processor'    => $r->processor ?? null,
                'ram'          => $r->ram ?? null,
                'storage'      => $r->storage ?? null,
                'battery'      => $r->battery ?? null,
                'camera'       => $r->camera ?? null,
                'os'           => $r->os ?? null,
                'connectivity' => $r->connectivity ?? null,
                'weight'       => $r->weight ?? null,
                'dimensions'   => $r->dimensions ?? null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }
        $this->line('  ' . count($rows) . ' spec sheets');
    }

    private function migratePriceHistory(): void
    {
        $this->line('→ Price History');
        $rows = $this->src->table('gadgets_pricehistory')->get();
        $gadgetMap     = DB::table('gadgets')->pluck('id', 'slug');
        $djangoGadgets = $this->src->table('gadgets_gadget')->pluck('slug', 'id');

        DB::table('price_histories')->truncate();
        foreach ($rows as $r) {
            $slug     = $djangoGadgets[$r->gadget_id] ?? null;
            $gadgetId = $slug ? ($gadgetMap[$slug] ?? null) : null;
            if (!$gadgetId) continue;
            DB::table('price_histories')->insert([
                'gadget_id'  => $gadgetId,
                'price'      => $r->price,
                'date'       => $r->date,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->line('  ' . count($rows) . ' price records');
    }

    private function migrateNewsArticles(): void
    {
        $this->line('→ News Articles');
        $rows          = $this->src->table('news_newsarticle')->get();
        $djangoUsers   = $this->src->table('auth_user')->pluck('email', 'id');
        $userMap        = DB::table('users')->pluck('id', 'email');
        $adminId        = DB::table('users')->where('is_admin', true)->value('id') ?? 1;

        DB::table('news_articles')->truncate();
        foreach ($rows as $r) {
            $userEmail = $djangoUsers[$r->author_id] ?? null;
            $userId    = $userEmail ? ($userMap[$userEmail] ?? $adminId) : $adminId;
            DB::table('news_articles')->insert([
                'user_id'          => $userId,
                'title'            => $r->title,
                'slug'             => $r->slug,
                'content'          => $r->content,
                'thumbnail'        => $r->thumbnail ?: null,
                'category'         => $r->category ?? 'tech',
                'meta_description' => $r->meta_description ?? null,
                'is_published'     => $r->is_published ?? true,
                'views_count'      => $r->views_count ?? 0,
                'created_at'       => $r->published_date ?? now(),
                'updated_at'       => $r->published_date ?? now(),
            ]);
        }
        $this->line('  ' . count($rows) . ' articles');
    }

    private function migrateTechGuides(): void
    {
        $this->line('→ Tech Guides');
        $rows          = $this->src->table('guides_techguide')->get();
        $djangoUsers   = $this->src->table('auth_user')->pluck('email', 'id');
        $userMap        = DB::table('users')->pluck('id', 'email');
        $adminId        = DB::table('users')->where('is_admin', true)->value('id') ?? 1;

        DB::table('tech_guides')->truncate();
        foreach ($rows as $r) {
            $userEmail = $djangoUsers[$r->author_id] ?? null;
            $userId    = $userEmail ? ($userMap[$userEmail] ?? $adminId) : $adminId;
            DB::table('tech_guides')->insert([
                'user_id'      => $userId,
                'title'        => $r->title,
                'slug'         => $r->slug,
                'content'      => $r->content,
                'thumbnail'    => $r->thumbnail ?: null,
                'is_published' => $r->is_published ?? true,
                'created_at'   => $r->created_at ?? now(),
                'updated_at'   => $r->updated_at ?? now(),
            ]);
        }
        $this->line('  ' . count($rows) . ' guides');
    }

    private function migrateOrders(): void
    {
        $this->line('→ Orders');
        $orders = $this->src->table('orders_order')->get();
        $djangoUsers   = $this->src->table('auth_user')->pluck('email', 'id');
        $userMap        = DB::table('users')->pluck('id', 'email');
        $gadgetMap      = DB::table('gadgets')->pluck('id', 'slug');
        $djangoGadgets  = $this->src->table('gadgets_gadget')->pluck('slug', 'id');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        foreach ($orders as $o) {
            $userEmail = $djangoUsers[$o->user_id] ?? null;
            $userId    = $userEmail ? ($userMap[$userEmail] ?? null) : null;

            $orderId = DB::table('orders')->insertGetId([
                'user_id'          => $userId,
                'first_name'       => $o->first_name ?? '',
                'last_name'        => $o->last_name ?? '',
                'email'            => $o->email ?? ($userEmail ?? ''),
                'phone_number'     => $o->phone_number ?? '',
                'shipping_address' => $o->shipping_address ?? '',
                'payment_method'   => $o->payment_method ?? 'cod',
                'total_amount'     => $o->total_amount ?? 0,
                'status'           => $o->status ?? 'pending',
                'is_paid'          => $o->is_paid ?? false,
                'created_at'       => $o->created_at ?? now(),
                'updated_at'       => $o->updated_at ?? now(),
            ]);

            $items = $this->src->table('orders_orderitem')->where('order_id', $o->id)->get();
            foreach ($items as $item) {
                $slug     = $djangoGadgets[$item->gadget_id] ?? null;
                $gadgetId = $slug ? ($gadgetMap[$slug] ?? null) : null;
                if (!$gadgetId) continue;
                DB::table('order_items')->insert([
                    'order_id'    => $orderId,
                    'gadget_id'   => $gadgetId,
                    'quantity'    => $item->quantity ?? 1,
                    'price'       => $item->price ?? 0,
                    'variant_info'=> $item->variant_info ?? null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
        $this->line('  ' . count($orders) . ' orders');
    }

    private function migrateWishlists(): void
    {
        $this->line('→ Wishlists');
        // accounts_userprofile_wishlist: userprofile_id → gadget_id
        $rows      = $this->src->table('accounts_userprofile_wishlist')->get();
        $profiles  = $this->src->table('accounts_userprofile')->pluck('user_id', 'id');
        $djangoUsers   = $this->src->table('auth_user')->pluck('email', 'id');
        $userMap        = DB::table('users')->pluck('id', 'email');
        $gadgetMap      = DB::table('gadgets')->pluck('id', 'slug');
        $djangoGadgets  = $this->src->table('gadgets_gadget')->pluck('slug', 'id');

        DB::table('wishlists')->truncate();
        foreach ($rows as $r) {
            $djangoUserId = $profiles[$r->userprofile_id] ?? null;
            $userEmail    = $djangoUserId ? ($djangoUsers[$djangoUserId] ?? null) : null;
            $userId       = $userEmail ? ($userMap[$userEmail] ?? null) : null;
            $slug         = $djangoGadgets[$r->gadget_id] ?? null;
            $gadgetId     = $slug ? ($gadgetMap[$slug] ?? null) : null;
            if (!$userId || !$gadgetId) continue;
            DB::table('wishlists')->insertOrIgnore([
                'user_id'    => $userId,
                'gadget_id'  => $gadgetId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->line('  ' . count($rows) . ' wishlist entries');
    }

    private function migrateReviews(): void
    {
        $this->line('→ Reviews (user comments)');
        // reviews_usercomment has user comments on gadgets
        $cols = collect($this->src->select('SHOW COLUMNS FROM reviews_usercomment'))->pluck('Field')->toArray();
        if (empty($cols)) {
            $this->line('  (no user comments table)');
            return;
        }

        $rows = $this->src->table('reviews_usercomment')->get();
        $djangoUsers   = $this->src->table('auth_user')->pluck('email', 'id');
        $userMap        = DB::table('users')->pluck('id', 'email');
        $gadgetMap      = DB::table('gadgets')->pluck('id', 'slug');
        $djangoGadgets  = $this->src->table('gadgets_gadget')->pluck('slug', 'id');

        DB::table('reviews')->truncate();
        foreach ($rows as $r) {
            $userEmail = $djangoUsers[$r->user_id] ?? null;
            $userId    = $userEmail ? ($userMap[$userEmail] ?? null) : null;
            $slug      = $djangoGadgets[$r->gadget_id] ?? null;
            $gadgetId  = $slug ? ($gadgetMap[$slug] ?? null) : null;
            if (!$userId || !$gadgetId) continue;
            DB::table('reviews')->insert([
                'user_id'    => $userId,
                'gadget_id'  => $gadgetId,
                'title'      => 'User Review',
                'slug'       => 'user-review-' . $r->id,
                'content'    => $r->comment ?? '',
                'rating'     => $r->rating ?? 5,
                'is_published' => true,
                'created_at' => $r->created_at ?? now(),
                'updated_at' => $r->updated_at ?? now(),
            ]);
        }
        $this->line('  ' . count($rows) . ' reviews');
    }
}
