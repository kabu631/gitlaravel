<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Gadget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class CompareController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $category   = $request->category;
        $slugs      = array_values(array_filter([$request->g1, $request->g2, $request->g3, $request->g4]));

        // If specific slugs are passed
        if (count($slugs) >= 1) {
            $selectedModels = Gadget::with(['brand', 'specs', 'category'])
                ->whereIn('slug', $slugs)
                ->get()
                ->sortBy(fn($g) => array_search($g->slug, $slugs))
                ->values();

            // Auto-detect category from first device if not passed
            if (!$category && $selectedModels->isNotEmpty()) {
                $category = $selectedModels->first()->category?->slug;
            }
        } else {
            $selectedModels = collect();
        }

        // Strict Same-Category Guarantee:
        // We ALWAYS compare the latest hot products of the SAME category (mobile-mobile, laptop-laptop, earbuds-earbuds, smartwatch-smartwatch)
        $targetCategory = $category ?: ($selectedModels->isNotEmpty() ? $selectedModels->first()->category?->slug : 'mobile') ?: 'mobile';
        $category = $targetCategory;

        // Filter out any devices that don't match the target same category
        $selectedModels = $selectedModels->filter(fn($g) => $g->category?->slug === $targetCategory)->values();

        // If fewer than 2 remain in this same category, backfill with top trending/latest products of that exact category
        if ($selectedModels->count() < 2) {
            $existingIds = $selectedModels->pluck('id')->toArray();
            $needed = 2 - $selectedModels->count();
            $fill = Gadget::with(['brand', 'specs', 'category'])
                ->whereNotNull('image')
                ->whereHas('category', fn($q) => $q->where('slug', $targetCategory))
                ->whereNotIn('id', $existingIds)
                ->orderBy('is_trending', 'desc')
                ->latest()
                ->take($needed)
                ->get();
            $selectedModels = $selectedModels->concat($fill)->values();
        }

        $selected = $selectedModels->toArray();
        $slugs    = $selectedModels->pluck('slug')->toArray();
        $prices   = array_filter(array_column($selected, 'price'));
        $minPrice = $prices ? min($prices) : null;

        // Load all gadgets belonging to this exact category so user can swap within same category
        $gadgets = $category
            ? Gadget::with(['brand', 'specs', 'category'])
                ->whereHas('category', fn($q) => $q->where('slug', $category))
                ->orderBy('is_trending', 'desc')
                ->latest()
                ->get()
            : collect();

        return Inertia::render('Compare/Index', [
            'categories'      => $categories,
            'category'        => $category,
            'gadgets'         => $gadgets,
            'selectedGadgets' => $selected,
            'slugs'           => $slugs,
            'minPrice'        => $minPrice,
        ]);
    }

    public function suggest(Request $request)
    {
        $data  = $request->validate(['slugs' => 'required|array|min:2|max:4', 'slugs.*' => 'string']);
        $slugs = $data['slugs'];

        $gadgets = Gadget::with(['brand', 'specs'])
            ->whereIn('slug', $slugs)
            ->get()
            ->sortBy(fn($g) => array_search($g->slug, array_values($slugs)))
            ->values();

        if ($gadgets->count() < 2) {
            return response()->json(['error' => 'Need at least 2 products.'], 422);
        }

        if (!\App\Services\AiService::isConfigured()) {
            return response()->json(['error' => 'AI service not configured. Add DEEPSEEK_API_KEY to .env'], 503);
        }

        $lines = $gadgets->map(function ($g) {
            $specs = $g->specs ? collect($g->specs->toArray())
                ->only(['display', 'processor', 'ram', 'storage', 'battery', 'camera', 'os'])
                ->map(fn($v, $k) => "$k: $v")
                ->implode(', ') : '';
            return "- {$g->brand?->name} {$g->name} (NPR " . number_format($g->price) . "): {$specs}";
        })->implode("\n");

        $text = \App\Services\AiService::chat([
            ['role' => 'system', 'content' => 'You are a tech expert at Git Infosys, Nepal\'s trusted gadget platform. Give clear, practical buying advice for Nepali customers. Use NPR for prices. Format your response with proper markdown: use ## for section headings, **bold** for key terms and product names, and bullet lists for features. Use a markdown comparison table when summarizing differences.'],
            ['role' => 'user',   'content' => "Compare these products and give a buying recommendation:\n\n{$lines}\n\nStructure your response as:\n## Comparison Summary\n(markdown table comparing key specs)\n\n## Who Should Buy Each?\n(one paragraph per product)\n\n## Overall Verdict\n(clear winner or use-case recommendation)"],
        ], [
            'max_tokens'  => 2048,
            'temperature' => 0.6,
            'timeout'     => 30,
        ]);

        if (!$text) {
            return response()->json(['error' => 'AI comparison service currently unavailable. Please try again.'], 500);
        }

        return response()->json(['suggestion' => $text]);
    }
}
