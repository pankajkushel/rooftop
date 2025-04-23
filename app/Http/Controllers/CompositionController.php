<?php

namespace App\Http\Controllers;

use App\Models\Composition;
use Illuminate\Http\Request;

class CompositionController extends Controller
{
    // Display all compositions
    public function index()
    {
        $compositions = Composition::orderBy('id', 'desc')->get();
        return view('admin.master.composition', compact('compositions'));
    }

    // Show form to create a new composition
    public function create()
    {
        return view('admin.compositions.create');
    }

    // Store a newly created composition
    public function store(Request $request)
    {
        $request->validate([
            'composition' => 'required|max:255|unique:compositions,composition',
        ]);

        Composition::create([
            'composition' => $request->composition,
        ]);

        return redirect()->route('compositions.index')->with('success', 'Composition added successfully');
    }

    // Show the form to edit the specified composition
    public function edit($id)
    {
        $composition = Composition::findOrFail($id);
        return response()->json($composition);
    }

    // Update the specified composition
    public function update(Request $request, $id)
    {
        $request->validate([
            'composition' => 'required|max:255',
        ]);

        $composition = Composition::findOrFail($id);
        $composition->update([
            'composition' => $request->composition,
        ]);

        return redirect()->route('compositions.index')->with('success', 'Composition updated successfully');
    }

    // Delete the specified composition
    public function destroy($id)
    {
        $composition = Composition::findOrFail($id);
        $composition->delete();

        return redirect()->route('compositions.index')->with('success', 'Composition deleted successfully');
    }
}
