<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class PCBuilderController extends Controller
{
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

        $prompt = "Build a PC recommendation for Nepal's market.\n\n"
            . "Budget: NPR {$budget}\n"
            . "Purpose: {$request->purpose}\n"
            . ($parts ? "Preferred components:\n{$parts}" : "No components pre-selected.")
            . "\n\nProvide a complete PC build using the following structure and markdown formatting:\n\n"
            . "## Recommended Build\n"
            . "Present every component as a markdown table with columns: Component | Model | Est. Price (NPR)\n"
            . "Include: CPU, GPU, RAM, Storage, Motherboard, PSU, Cooling, Case.\n\n"
            . "## Total Estimated Cost\n"
            . "Sum of all components in NPR.\n\n"
            . "## Performance Summary\n"
            . "What this build handles for the stated purpose.\n\n"
            . "## Compatibility Notes\n"
            . "Important compatibility checks (use a bullet list).\n\n"
            . "## Upgrade Path\n"
            . "What to upgrade first if budget increases (use a bullet list).\n\n"
            . "IMPORTANT: Use proper markdown (** for bold, ## for headings, markdown tables). "
            . "Do NOT recommend any rival stores (like Hukut, Daraz, Nagmani, etc). Tell the user they can buy all these components directly from Git Infosys.\n"
            . "Use realistic Nepal market prices in NPR.";

        $openRouterKey = config('services.openrouter.key');
        if (!$openRouterKey) {
            return response()->json([
                'error' => 'AI service is not configured. Please add OPENROUTER_API_KEY to your .env file.',
            ], 503);
        }

        $text = $this->callOpenRouter($openRouterKey, $prompt);
        if (!$text) {
            return response()->json([
                'error' => 'The AI service is currently overwhelmed (Rate Limit). Please try again in a moment.',
            ], 500);
        }

        return response()->json(['recommendation' => $text]);
    }

    // ── OpenRouter API (Free Tier) ───────────────────────────────────────────
    private function callOpenRouter(string $key, string $prompt): ?string
    {
        $response = Http::timeout(60)
            ->withoutVerifying()
            ->withToken($key)
            ->withHeaders([
                'HTTP-Referer' => config('app.url'),
                'X-Title'      => config('app.name'),
            ])
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'openrouter/auto',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert PC builder acting on behalf of Git Infosys in Nepal. You know current component prices in NPR. Always recommend specific models with realistic NPR prices. NEVER mention or recommend competitors like Hukut, Daraz, Nagmani IT, or CG Digital. Always tell users they can purchase their build from Git Infosys.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 4096,
                'temperature' => 0.6,
            ]);

        if ($response->failed()) {
            \Log::error('OpenRouter API failed', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        }

        return $response->json('choices.0.message.content')
            ?? $response->json('choices.0.message.reasoning');
    }

}
