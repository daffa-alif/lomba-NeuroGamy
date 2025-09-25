<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    /**
     * Show chatbot view
     */
    public function chatbot()
    {
        return view('Summary.index');
    }

    /**
     * Send prompt to Gemini API
     */
    public function generate(Request $request)
    {
        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            return response()->json([
                'error' => 'Gemini API key missing'
            ], 500);
        }

        $data = $request->validate([
            'prompt' => 'required|string',
        ]);

        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

        try {
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'X-goog-api-key'=> $apiKey,
            ])->post($endpoint, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $data['prompt']]
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                return response()->json([
                    'error' => 'Gemini API request failed',
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ], 502);
            }

            $json = $response->json();

            // Extract first candidate text safely
            $output = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;

            return response()->json([
                'output' => $output,
                'raw'    => $json, // keep raw response for debugging
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error'   => 'Exception occurred',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
