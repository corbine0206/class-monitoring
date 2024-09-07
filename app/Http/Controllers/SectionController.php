<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{

    public function index()
    {   
        $sections = Section::where('user_id', Auth::id())->get();
        return view('sections.index', compact('sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Section::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('sections.index')->with('success', 'Section created successfully.');
    }

    // Method to update an existing section
    public function update(Request $request, Section $section)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Ensure the section belongs to the authenticated user
        if ($section->user_id != Auth::id()) {
            return redirect()->route('sections.index')->with('error', 'You are not authorized to edit this section.');
        }

        $section->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('sections.index')->with('success', 'Section updated successfully.');
    }

    // Method to delete a section
    public function destroy(Section $section)
    {
        // Ensure the section belongs to the authenticated user
        if ($section->user_id != Auth::id()) {
            return redirect()->route('sections.index')->with('error', 'You are not authorized to delete this section.');
        }

        $section->delete();

        return redirect()->route('sections.index')->with('success', 'Section deleted successfully.');
    }
}
