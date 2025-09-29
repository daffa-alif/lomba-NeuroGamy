<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser; // butuh package smalot/pdfparser

class ReadingController extends Controller
{
    public function index($bookId)
    {
        $book = Book::findOrFail($bookId);
        return view('Reading.Index', compact('book'));
    }

    public function summarize(Request $request, $bookId)
    {
        $book = Book::findOrFail($bookId);

        // --- 1. Ambil teks dari file PDF ---
        $pdfPath = storage_path('app/public/books/' . $book->file_name);
        if (!file_exists($pdfPath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $parser = new Parser();
        $pdf = $parser->parseFile($pdfPath);
        $text = $pdf->getText();

        // Potong teks biar ga terlalu panjang (Gemini ada limit token)
        $excerpt = substr($text, 0, 3000);

        // --- 2. Panggil Gemini API ---
        $apiKey = config('services.gemini.api_key');
        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

        $prompt = "Ringkas isi buku berikut dalam bahasa Indonesia:\n\n" . $excerpt;

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
                'error' => 'Gemini API failed',
                'status' => $response->status(),
                'body'   => $response->body(),
            ], 500);
        }

        $json = $response->json();
        $output = $json['candidates'][0]['content']['parts'][0]['text'] ?? "No summary";

        return response()->json([
            'summary' => $output,
        ]);
    }
}
