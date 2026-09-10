<?php

namespace App\Http\Controllers;

use App\Services\AiService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message'           => 'required|string|max:1000',
            'history'           => 'array|max:20',
            'history.*.role'    => 'required|in:user,assistant',
            'history.*.content' => 'required|string|max:2000',
        ]);

        if (!AiService::isConfigured()) {
            return response()->json(['error' => 'AI service not configured. Add DEEPSEEK_API_KEY to .env'], 503);
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

        $reply = AiService::chat($messages, [
            'temperature' => 0.7,
            'max_tokens'  => 4096,
            'timeout'     => 25,
        ]);

        if (!$reply) {
            return response()->json(['error' => 'Could not get a response from AI service. Please try again.'], 500);
        }

        return response()->json(['reply' => $reply]);
    }
}

