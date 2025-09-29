<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Http;

class ReadingController extends Controller
{
    public function index($id)
    {
        // Ambil data buku
        $book = Book::findOrFail($id);

        return view('Reading.Index', compact('book'));
    }

    public function summarize(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $apiKey = config('services.gemini.api_key');
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API key missing'], 500);
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
            $output = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;

            return response()->json([
                'output' => $output,
                'raw'    => $json, // debugging
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error'   => 'Exception occurred',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
