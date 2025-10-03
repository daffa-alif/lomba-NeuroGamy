<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ScoreLogs;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        $logs = ScoreLogs::where('user_id', $userId)->with('book')->get();

        $totalBooks = $logs->groupBy('books_id')->count();
        $totalQuizzes = $logs->count();
        $averageScore = $logs->avg('score');
        $highestScore = $logs->max('score');
        $lowestScore = $logs->min('score');

        return view('profile.index', [
            'logs' => $logs,
            'totalBooks' => $totalBooks,
            'totalQuizzes' => $totalQuizzes,
            'averageScore' => round($averageScore, 2),
            'highestScore' => $highestScore,
            'lowestScore' => $lowestScore,
            'username' => $user->name,
            'profileImage' => $user->profile_image ?? null,
        ]);
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Update nama
        $user->name = $validated['name'];

        // Jika ada upload foto
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
