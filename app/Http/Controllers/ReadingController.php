<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReadingController extends Controller
{
    /**
     * Show the reading view (PDF + AI summary).
     */

   public function index($id)
{
    $book = Book::findOrFail($id);

    // Ambil jumlah halaman (contoh: dari DB kolom page_count)
    $totalPages = $book->page_count ?? 0;

    // Minimal 5 halaman
    $maxPages = ($totalPages < 5) ? $totalPages : 5;

    return view('Reading.index', [
        'book' => $book,
        'totalPages' => $totalPages,
        'maxPages' => $maxPages,
    ]);
}


    /**
     * Summarize the given book page using Gemini.
     */
    public function summarize(Request $request, Book $book)
    {
        $request->validate([
            'page' => 'required|integer|min:1',
        ]);

        $page = $request->input('page');
        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            return response()->json([
                'error' => 'Gemini API key missing'
            ], 500);
        }

        // Prompt untuk AI
        $prompt = "Buat ringkasan singkat untuk halaman {$page} dari buku berjudul '{$book->book_title}', dan jangan berikan respon ragu seperti kata 'kemungkinan', 'mungkin', karena digunakan untuk aplikasi summarize.";

        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

        try {
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'X-goog-api-key'=> $apiKey,
            ])->post($endpoint, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                return response()->json([
                    'error'  => 'Gemini API request failed',
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ], 502);
            }

            $json = $response->json();

            // Ambil teks pertama dari hasil AI
            $output = $json['candidates'][0]['content']['parts'][0]['text'] ?? null;

            return response()->json([
                'output' => $output ?? 'AI tidak mengembalikan ringkasan.',
                'raw'    => $json, // untuk debugging
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'error'   => 'Exception occurred',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
