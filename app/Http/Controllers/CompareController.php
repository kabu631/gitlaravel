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
        $category   = $request->category;
        $categories = Category::all();

        $gadgets         = $category
            ? Gadget::with(['brand', 'specs'])->whereHas('category', fn($q) => $q->where('slug', $category))->get()
            : collect();

        $slugs  = array_filter([$request->g1, $request->g2, $request->g3, $request->g4]);
        $selected = [];
        $minPrice = null;

        if (count($slugs) >= 2) {
            $selected = Gadget::with(['brand', 'specs'])
                ->whereIn('slug', $slugs)
                ->get()
                ->sortBy(fn($g) => array_search($g->slug, $slugs))
                ->values()
                ->toArray();

            $prices   = array_filter(array_column($selected, 'price'));
            $minPrice = $prices ? min($prices) : null;
        }

        return Inertia::render('Compare/Index', [
            'categories'      => $categories,
            'category'        => $category,
            'gadgets'         => $gadgets,
            'selectedGadgets' => $selected,
            'slugs'           => array_values($slugs),
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

        $apiKey = config('services.openrouter.key');
        if (!$apiKey) {
            return response()->json(['error' => 'AI service not configured.'], 503);
        }

        $lines = $gadgets->map(function ($g) {
            $specs = $g->specs ? collect($g->specs->toArray())
                ->only(['display', 'processor', 'ram', 'storage', 'battery', 'camera', 'os'])
                ->map(fn($v, $k) => "$k: $v")
                ->implode(', ') : '';
            return "- {$g->brand?->name} {$g->name} (NPR " . number_format($g->price) . "): {$specs}";
        })->implode("\n");

        $response = Http::timeout(25)
            ->withoutVerifying()
            ->withToken($apiKey)
            ->withHeaders([
                'HTTP-Referer' => config('app.url'),
                'X-Title' => config('app.name'),
            ])
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model'       => 'openrouter/free',
                'messages'    => [
                    ['role' => 'system', 'content' => 'You are a tech expert at Git Infosys, Nepal\'s trusted gadget platform. Give clear, practical buying advice for Nepali customers. Use NPR for prices. Be concise and structured.'],
                    ['role' => 'user',   'content' => "Compare these products and give a buying recommendation:\n\n{$lines}\n\nFor each product, say who should buy it and for what purpose. End with a clear overall verdict."],
                ],
                'max_tokens'  => 700,
                'temperature' => 0.6,
            ]);

        if ($response->failed()) {
            $errorDetail = $response->json('error.message') ?? $response->body();
            return response()->json(['error' => 'AI Error: ' . $errorDetail], 500);
        }

        $text = $response->json('choices.0.message.content') ?? 'No suggestion available.';
        return response()->json(['suggestion' => $text]);
    }
}
