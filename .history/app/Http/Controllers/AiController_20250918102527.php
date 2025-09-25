<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiController extends Controller
{
    /**
     * Make a request to Gemini AI using API key from config/services.php
     * Example: POST /api/ai/generate
     */
    public function generate(Request $request)
    {
        // get API key from config (safe for config:cache)
        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            return response()->json([
                'error' => 'Gemini API key not configured.'
            ], 500);
        }

        // validate input (adjust rules to your needs)
        $data = $request->validate([
            'prompt' => ['required', 'string'],
            'max_tokens' => ['sometimes', 'integer'],
        ]);

        // build the remote request
        $endpoint = rtrim(config('services.gemini.base_url', 'https://api.gemini.example'), '/') . '/v1/generate';

        try {
            $response = Http::withHeaders([
                // Use the expected header for the Gemini API (adjust if Gemini expects a different header)
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ])->post($endpoint, [
                'prompt' => $data['prompt'],
                'max_tokens' => $data['max_tokens'] ?? 512,
            ]);

            // If remote returns non-2xx
            if (! $response->successful()) {
                // log for debugging
                Log::warning('Gemini API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return response()->json([
                    'error' => 'Gemini API request failed.',
                    'status' => $response->status(),
                    'body' => $response->json(),
                ], 502);
            }

            // return Gemini response (or adapt shape as you like)
            return response()->json($response->json());
        } catch (\Throwable $e) {
            Log::error('Exception while calling Gemini API: ' . $e->getMessage());

            return response()->json([
                'error' => 'Exception while calling Gemini API.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
