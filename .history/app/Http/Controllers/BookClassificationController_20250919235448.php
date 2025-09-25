<?php

namespace App\Http\Controllers;

use App\Models\BookClassification;
use Illuminate\Http\Request;

class BookClassificationController extends Controller
{
    public function index()
    {
        $classifications = BookClassification::latest()->get();
        return view('admin.classifications.index', compact('classifications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'classification' => 'required|string|max:255|unique:book_classifications,classification',
        ]);

        $classification = BookClassification::create([
            'classification' => $request->classification,
        ]);

        // AJAX response
        return response()->json([
            'success' => true,
            'data' => $classification
        ]);
    }

    public function edit(BookClassification $classification)
    {
        return view('admin.classifications.edit', compact('classification'));
    }

    public function update(Request $request, BookClassification $classification)
    {
        $request->validate([
            'classification' => 'required|string|max:255|unique:book_classifications,classification,' . $classification->id,
        ]);

        $classification->update([
            'classification' => $request->classification,
        ]);

        return redirect()->route('classifications.index')->with('success', 'Classification updated successfully.');
    }

    public function destroy(BookClassification $classification)
    {
        $classification->delete();
        return response()->json(['success' => true]);
    }
}
