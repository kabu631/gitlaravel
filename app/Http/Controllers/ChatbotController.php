<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    private const ENDPOINT = 'https://integrate.api.nvidia.com/v1/chat/completions';
    private const MODEL     = 'meta/llama-3.1-8b-instruct';

    public function chat(Request $request)
    {
        $request->validate([
            'message'           => 'required|string|max:1000',
            'history'           => 'array|max:20',
            'history.*.role'    => 'required|in:user,assistant',
            'history.*.content' => 'required|string|max:2000',
        ]);

        $apiKey = config('services.nvidia.key');
        if (!$apiKey) {
            return response()->json(['error' => 'AI service not configured. Add NVIDIA_API_KEY to .env'], 503);
        }

        $messages = array_merge(
            [[
                'role'    => 'system',
                'content' => 'You are TechBot, a friendly AI assistant for Git Infosys — Nepal\'s trusted tech price tracking and review platform. Help users with: product recommendations, price comparisons, tech specs, buying advice, PC building, and gadget questions. Keep answers concise and friendly. Use NPR for prices when relevant. For current live prices or availability, direct users to browse the website.',
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
            ->post(self::ENDPOINT, [
                'model'       => self::MODEL,
                'messages'    => $messages,
                'max_tokens'  => 600,
                'temperature' => 0.7,
            ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Could not get a response. Please try again.'], 500);
        }

        $text = $response->json('choices.0.message.content') ?? 'Sorry, I could not process that.';
        return response()->json(['reply' => $text]);
    }
}
