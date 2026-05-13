<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class PCBuilderController extends Controller
{
    private const ENDPOINT = 'https://integrate.api.nvidia.com/v1/chat/completions';
    private const MODEL     = 'meta/llama-3.1-70b-instruct';

    public function index()
    {
        return Inertia::render('PCBuilder/Index');
    }

    public function recommend(Request $request)
    {
        $request->validate([
            'budget'      => 'required|numeric|min:5000',
            'purpose'     => 'required|string|max:100',
            'cpu'         => 'nullable|string|max:100',
            'gpu'         => 'nullable|string|max:100',
            'ram'         => 'nullable|string|max:50',
            'storage'     => 'nullable|string|max:100',
            'motherboard' => 'nullable|string|max:100',
            'psu'         => 'nullable|string|max:50',
            'cooling'     => 'nullable|string|max:50',
            'case'        => 'nullable|string|max:100',
        ]);

        $apiKey = config('services.nvidia.key');
        if (!$apiKey) {
            return response()->json(['error' => 'AI service not configured. Add NVIDIA_API_KEY to .env'], 503);
        }

        $budget = number_format((int) $request->budget);
        $parts  = collect([
            'CPU'         => $request->cpu,
            'GPU'         => $request->gpu,
            'RAM'         => $request->ram,
            'Storage'     => $request->storage,
            'Motherboard' => $request->motherboard,
            'PSU'         => $request->psu,
            'Cooling'     => $request->cooling,
            'Case'        => $request->case,
        ])->filter()->map(fn($v, $k) => "- $k: $v")->implode("\n");

        $userMessage = "Build a PC recommendation for Nepal's market.

Budget: NPR {$budget}
Purpose: {$request->purpose}
" . ($parts ? "Preferred components:\n{$parts}" : "No components pre-selected.") . "

Provide a complete PC build with these sections:
1. **Recommended Build** - Every component (CPU, GPU, RAM, Storage, Motherboard, PSU, Cooling, Case) with specific model names and estimated NPR prices
2. **Total Estimated Cost** - Sum of all components
3. **Performance Summary** - What this build handles for the stated purpose
4. **Compatibility Notes** - Important compatibility checks
5. **Where to Buy in Nepal** - Daraz, Hukut, Nagmani IT, CG Digital, etc.
6. **Upgrade Path** - What to upgrade first if budget increases

Use realistic Nepal market prices in NPR.";

        $response = Http::timeout(45)
            ->withoutVerifying()
            ->withToken($apiKey)
            ->post(self::ENDPOINT, [
                'model'       => self::MODEL,
                'messages'    => [
                    [
                        'role'    => 'system',
                        'content' => 'You are an expert PC builder specializing in Nepal\'s computer hardware market. You know current component prices in NPR and local availability. Always recommend specific models with realistic NPR prices.',
                    ],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'max_tokens'  => 2048,
                'temperature' => 0.6,
            ]);

        if ($response->failed()) {
            return response()->json(['error' => 'AI request failed: ' . $response->body()], 500);
        }

        $text = $response->json('choices.0.message.content') ?? 'No recommendation received.';
        return response()->json(['recommendation' => $text]);
    }
}
