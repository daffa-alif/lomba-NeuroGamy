<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScoreLog;
use Illuminate\Support\Facades\Auth;

class ScoreLogsController extends Controller
{
  public function store(Request $request)
{
    try {
        $log = ScoreLog::create([
            'book_id' => $request->book_id,
            'title'   => $request->title,
            'page'    => $request->page,
            'summary' => $request->summary ?? null,
            'user_id' => auth()->id()
        ]);

        return response()->json(['success' => true, 'log' => $log]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error'   => $e->getMessage()
        ], 500);
    }
}

}
