<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function chatbot()
    {
        // show the chatbot view
        return view('Summary.index');
    }

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

    try {
        $endpoint = rtrim(config('services.gemini.base_url', 'https://api.gemini.example'), '/') . '/v1/generate';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept' => 'application/json',
        ])->post($endpoint, [
            'prompt' => $data['prompt'],
        ]);

        if ($response->failed()) {
            return response()->json([
                'error' => 'Gemini API failed',
                'status' => $response->status(),
                'body' => $response->body(), // keep raw body for debug
            ], 502);
        }

        return response()->json($response->json());
    } catch (\Throwable $e) {
        return response()->json([
            'error' => 'Exception occurred',
            'message' => $e->getMessage(),
        ], 500);
    }
}

}
