<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    private const DEEPSEEK_ENDPOINT = 'https://api.deepseek.com/chat/completions';
    private const OPENROUTER_ENDPOINT = 'https://openrouter.ai/api/v1/chat/completions';

    /**
     * Determine which AI provider is currently active and configured.
     */
    public static function getActiveProvider(): ?string
    {
        if (config('services.deepseek.key')) {
            return 'deepseek';
        }
        if (config('services.openrouter.key')) {
            return 'openrouter';
        }
        return null;
    }

    /**
     * Check if any AI service is configured.
     */
    public static function isConfigured(): bool
    {
        return self::getActiveProvider() !== null;
    }

    /**
     * Send a chat completion request to the active AI provider.
     *
     * @param array $messages Array of ['role' => '...', 'content' => '...']
     * @param array $options Additional options: temperature, max_tokens, timeout
     * @return string|null
     */
    public static function chat(array $messages, array $options = []): ?string
    {
        $provider = self::getActiveProvider();
        if (!$provider) {
            return null;
        }

        $temperature = $options['temperature'] ?? 0.7;
        $maxTokens   = $options['max_tokens'] ?? 4096;
        $timeout     = $options['timeout'] ?? 30;

        if ($provider === 'deepseek') {
            return self::callDeepSeek($messages, $temperature, $maxTokens, $timeout);
        }

        return self::callOpenRouter($messages, $temperature, $maxTokens, $timeout);
    }

    /**
     * Call DeepSeek API directly.
     */
    private static function callDeepSeek(array $messages, float $temperature, int $maxTokens, int $timeout): ?string
    {
        $key   = config('services.deepseek.key');
        $model = config('services.deepseek.model', 'deepseek-chat');

        try {
            $response = Http::timeout($timeout)
                ->withoutVerifying()
                ->withToken($key)
                ->post(self::DEEPSEEK_ENDPOINT, [
                    'model'       => $model,
                    'messages'    => $messages,
                    'max_tokens'  => $maxTokens,
                    'temperature' => $temperature,
                ]);

            if ($response->failed()) {
                Log::error('DeepSeek API failed', [
                    'status' => $response->status(),
                    'body'   => $response->body()
                ]);
                return null;
            }

            return $response->json('choices.0.message.content')
                ?? $response->json('choices.0.message.reasoning');
        } catch (\Throwable $e) {
            Log::error('DeepSeek API exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Call OpenRouter API as fallback.
     */
    private static function callOpenRouter(array $messages, float $temperature, int $maxTokens, int $timeout): ?string
    {
        $key = config('services.openrouter.key');

        try {
            $response = Http::timeout($timeout)
                ->withoutVerifying()
                ->withToken($key)
                ->withHeaders([
                    'HTTP-Referer' => config('app.url'),
                    'X-Title'      => config('app.name'),
                ])
                ->post(self::OPENROUTER_ENDPOINT, [
                    'model'       => 'openrouter/auto',
                    'messages'    => $messages,
                    'max_tokens'  => $maxTokens,
                    'temperature' => $temperature,
                ]);

            if ($response->failed()) {
                Log::error('OpenRouter API failed', [
                    'status' => $response->status(),
                    'body'   => $response->body()
                ]);
                return null;
            }

            return $response->json('choices.0.message.content')
                ?? $response->json('choices.0.message.reasoning');
        } catch (\Throwable $e) {
            Log::error('OpenRouter API exception: ' . $e->getMessage());
            return null;
        }
    }
}
