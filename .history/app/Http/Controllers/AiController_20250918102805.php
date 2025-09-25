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

        $data = $request->validate([
            'prompt' => ['required', 'string'],
        ]);

        $endpoint = rtrim(config('services.gemini.base_url', 'https://api.gemini.example'), '/') . '/v1/generate';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept' => 'application/json',
        ])->post($endpoint, [
            'prompt' => $data['prompt'],
        ]);

        return response()->json($response->json());
    }
}
