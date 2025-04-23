<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    // Constructor to handle permissions or middleware
    public function __construct()
    {
        $this->middleware('auth'); // Example middleware for authentication
    }

    // Display a listing of colors
    public function index()
    {
        $colors = Color::all(); // Retrieve all colors
        return view('admin.master.color', compact('colors')); // Return the view with colors data
    }

    // Show the form for creating a new color
    public function create()
    {
        return view('admin.colors.create'); // Render create form (optional)
    }

    // Store a newly created color in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'color' => 'required|max:30|unique:colors,color', // Ensure the color is unique
        ]);
        Color::create($validated);
        return redirect()->route('colors.index')->with('success', 'Color added successfully!');
    }

    // Show the form for editing the specified color
    public function edit(Color $color)
    {
        return response()->json($color); // Return color data for editing
    }

    // Update the specified color in storage
    public function update(Request $request, Color $color)
    {
        // Validate the request
        $validated = $request->validate([
            'color' => 'required|max:255',
        ]);

        // Update the color
        $color->update($validated);

        // Redirect back with success message
        return redirect()->route('colors.index')->with('success', 'Color updated successfully!');
    }

    // Remove the specified color from storage
    public function destroy(Color $color)
    {
        // Delete the color
        $color->delete();

        // Redirect back with success message
        return redirect()->route('colors.index')->with('success', 'Color deleted successfully!');
    }
}
