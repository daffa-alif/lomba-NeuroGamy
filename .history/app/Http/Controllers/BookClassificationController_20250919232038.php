<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookClassification;
use Illuminate\Http\Request;

class BookClassificationController extends Controller
{
  public function store(Request $request)
    {
        $request->validate([
            'classification' => 'required|string|max:255|unique:book_classifications,classification',
        ]);

        $classification = BookClassification::create($request->all());

        return response()->json($classification);
    }
}
