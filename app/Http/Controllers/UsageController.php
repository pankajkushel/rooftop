<?php

namespace App\Http\Controllers;

use App\Models\Usage;
use Illuminate\Http\Request;

class UsageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  // Optional: Ensure only authenticated users can access the usage management pages
    }

    // Display a listing of all usages
    public function index()
    {
        $usages = Usage::orderBy('id', 'desc')->get();  // Retrieve all usages from the database
        return view('admin.master.usage', compact('usages'));  // Return the view with the usages
    }

    // Show the form for creating a new usage
    public function create()
    {
        return view('admin.usages.create');  // Show the form for adding a new usage
    }

    // Store a newly created usage in the database
    public function store(Request $request)
    {
        $request->validate([
            'usage' => 'required|max:255|unique:usages,usages',
        ]);

        Usage::create([
            'usages' => $request->usage,
        ]);

        return redirect()->route('usages.index')->with('success', 'Usage added successfully');
    }


    // Show the form for editing the specified usage
    public function edit(Usage $usage)
    {
        return response()->json($usage); // Show the edit form with the usage data
    }

    // Update the specified usage in the database
    public function update(Request $request, Usage $usage)
    {
        $request->validate([
            'usage' => 'required|max:255|unique:usages,usages',
        ]);

        $usage->update([
            'usages' => $request->usage,  // Update the usage in the database
        ]);

        return redirect()->route('usages.index')->with('success', 'Usage updated successfully');
    }

    // Remove the specified usage from the database
    public function destroy(Usage $usage)
    {
        $usage->delete();  // Delete the usage from the database

        return redirect()->route('usages.index')->with('success', 'Usage deleted successfully');
    }
}
