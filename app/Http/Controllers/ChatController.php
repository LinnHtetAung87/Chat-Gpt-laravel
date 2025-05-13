<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class ChatController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function handleChat(Request $request): string
    {

        try {
            /** @var array $response */
            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "Authorization" => "Bearer " . env('OPENROUTER_API_KEY'),
                "HTTP-Referer" => "http://127.0.0.1:8000/", // Optional
                "X-Title" => "Chat-GPT-laravel", // Optional
            ])->timeout(60)

            ->post('https://openrouter.ai/api/v1/chat/completions', [
                "model" => 'opengvlab/internvl3-2b:free',
                // "model" => 'qwen/qwen3-0.6b-04-28:free',
                "messages" => [
                    [
                        "role" => "user",
                        "content" => $request->input('content', 'What is the meaning of life?')
                    ]
                ],
                "temperature" => 0,
                "max_tokens" => 2048
            ])->json();
            return $response['choices'][0]['message']['content'] ?? 'No response from model.';
        } catch (Throwable $e) {
            return "Error: " . $e->getMessage();
        }
    }
}