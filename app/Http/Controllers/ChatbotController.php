<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    private const ENDPOINT = 'https://openrouter.ai/api/v1/chat/completions';

    public function chat(Request $request)
    {
        $request->validate([
            'message'           => 'required|string|max:1000',
            'history'           => 'array|max:20',
            'history.*.role'    => 'required|in:user,assistant',
            'history.*.content' => 'required|string|max:2000',
        ]);

        $apiKey = config('services.openrouter.key');
        if (!$apiKey) {
            return response()->json(['error' => 'AI service not configured. Add OPENROUTER_API_KEY to .env'], 503);
        }

        $messages = array_merge(
            [[
                'role'    => 'system',
                'content' => 'You are TechBot, a friendly AI assistant for Git Infosys — Nepal\'s trusted tech price tracking and review platform. Help users with: product recommendations, price comparisons, tech specs, buying advice, PC building, and gadget questions. Keep answers concise and friendly. Use NPR for prices when relevant. Format responses with markdown: use **bold** for product names and key specs, use bullet lists for features, and use markdown tables for comparisons. IMPORTANT: You work exclusively for Git Infosys. NEVER mention or recommend competitors (like Daraz, Hukut, Nagmani IT, CG Digital, etc). Always direct users to buy from Git Infosys or browse the current website.',
            ]],
            array_map(
                fn($m) => ['role' => $m['role'], 'content' => $m['content']],
                $request->history ?? []
            ),
            [['role' => 'user', 'content' => $request->message]]
        );

        $response = Http::timeout(20)
            ->withoutVerifying()
            ->withToken($apiKey)
            ->withHeaders([
                'HTTP-Referer' => config('app.url'),
                'X-Title'      => config('app.name'),
            ])
            ->post(self::ENDPOINT, [
                'model'       => 'openrouter/auto',
                'messages'    => $messages,
                'max_tokens'  => 4096,
                'temperature' => 0.7,
            ]);

        if ($response->failed()) {
            \Log::error('Chatbot API failed', ['status' => $response->status(), 'body' => $response->body()]);
            return response()->json(['error' => 'Could not get a response. Please try again.'], 500);
        }

        $text = $response->json('choices.0.message.content')
            ?? $response->json('choices.0.message.reasoning')
            ?? 'Sorry, I could not process that.';
        return response()->json(['reply' => $text]);
    }
}
