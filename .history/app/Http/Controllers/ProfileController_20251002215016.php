<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ScoreLogs;

class ProfileController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Ambil semua log user
        $logs = ScoreLogs::where('user_id', $userId)->with('book')->get();

        // Statistik
        $totalBooks = $logs->groupBy('books_id')->count(); // jumlah buku unik
        $totalQuizzes = $logs->count(); // jumlah semua attempt/log
        $averageScore = $logs->avg('score'); // rata-rata skor
        $highestScore = $logs->max('score'); // skor tertinggi
        $lowestScore = $logs->min('score');  // skor terendah

        return view('profile.index', [
            'logs' => $logs,
            'totalBooks' => $totalBooks,
            'totalQuizzes' => $totalQuizzes,
            'averageScore' => round($averageScore, 2),
            'highestScore' => $highestScore,
            'lowestScore' => $lowestScore,
        ]);
    }
}
