<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\GadgetController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PCBuilderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Products & Gadgets
Route::get('/products', [GadgetController::class, 'index'])->name('gadgets.index');
Route::get('/products/{slug}', [GadgetController::class, 'show'])->name('gadgets.show');
Route::get('/gadgets', [GadgetController::class, 'index']);
Route::get('/gadgets/{slug}', [GadgetController::class, 'show']);
Route::post('/products/{slug}/comment', [GadgetController::class, 'addComment'])->middleware('auth')->name('gadgets.comment');

// Brands
Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
Route::get('/brands/{slug}', [BrandController::class, 'show'])->name('brands.show');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{slug}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');

// Checkout & Orders
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order/success/{orderId}', [CheckoutController::class, 'success'])->name('order.success');

// News & Rumors
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/upcoming-launches', fn() => redirect()->route('news.index', ['category' => 'rumors']))->name('upcoming-launches');
Route::get('/rumors', fn() => redirect()->route('news.index', ['category' => 'rumors']))->name('rumors');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Reviews
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/reviews/{slug}', [ReviewController::class, 'show'])->name('reviews.show');
Route::post('/reviews/{id}/react', [ReviewController::class, 'react'])
    ->middleware('throttle:interactions')
    ->name('reviews.react');

// Guides
Route::get('/guides', [GuideController::class, 'index'])->name('guides.index');
Route::get('/guides/{slug}', [GuideController::class, 'show'])->name('guides.show');

// Compare
Route::get('/compare', [CompareController::class, 'index'])->name('compare.index');
Route::post('/compare/ai-suggest', [CompareController::class, 'suggest'])
    ->middleware('throttle:ai')
    ->name('compare.suggest');

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// PC Builder
Route::get('/pc-builder', [PCBuilderController::class, 'index'])->name('pcbuilder.index');
Route::post('/pc-builder/recommend', [PCBuilderController::class, 'recommend'])
    ->middleware('throttle:ai')
    ->name('pcbuilder.recommend');

// AI Chatbot
Route::post('/chatbot/chat', [ChatbotController::class, 'chat'])
    ->middleware('throttle:ai')
    ->name('chatbot.chat');

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/price-tracker', [PageController::class, 'priceTracker'])->name('pages.price-tracker');
Route::get('/tech-lab', [PageController::class, 'techLab'])->name('pages.tech-lab');
Route::post('/tech-lab/shootout/{id}/vote', [PageController::class, 'voteShootout'])
    ->middleware('throttle:interactions')
    ->name('pages.shootout.vote');
Route::get('/careers', [PageController::class, 'careers'])->name('pages.careers');
Route::post('/newsletter', [NewsletterController::class, 'store'])
    ->middleware('throttle:contact')
    ->name('newsletter.store');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::post('/contact', [PageController::class, 'contactStore'])
    ->middleware('throttle:contact')
    ->name('pages.contact.store');
Route::get('/services', [PageController::class, 'services'])->name('pages.services');
Route::get('/terms-and-conditions', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('pages.privacy');

// Wishlist & Profile (auth required)
Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{slug}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

$adminPath = '/' . ltrim(config('filament.path', 'secure-admin'), '/');
if (config('filament.path', 'secure-admin') !== 'admin') {
    Route::redirect('/admin', $adminPath);
}
Route::redirect($adminPath . '/brands/create', $adminPath . '/brands');
Route::redirect($adminPath . '/categories/create', $adminPath . '/categories');
Route::redirect($adminPath . '/sliders/create', $adminPath . '/sliders');
Route::redirect($adminPath . '/bank-partners/create', $adminPath . '/bank-partners');
Route::redirect($adminPath . '/authorized-service-centers/create', $adminPath . '/authorized-service-centers');
Route::redirect($adminPath . '/carrier-frequency-bands/create', $adminPath . '/carrier-frequency-bands');
Route::redirect($adminPath . '/upcoming-launches/create', $adminPath . '/upcoming-launches');
