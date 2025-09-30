<?php

namespace App\Http\Controllers;

use App\Models\ScoreLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoreLogsController extends Controller
{
 public function store(Request $request)
{
    dd($request->all(), Auth::id());
}

}
