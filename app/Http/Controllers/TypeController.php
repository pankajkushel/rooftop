<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    // Display a listing of the types
    public function index()
    {
        // Retrieve all types from the database
        $types = Type::orderBy('id', 'desc')->get();

        // Return the view with types data
        return view('admin.master.type', compact('types'));
    }

    // Show the form to create a new type
    public function create()
    {
        // This is handled by the modal in the view, no need to return anything here.
    }

    // Store a newly created type in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|string|max:255|unique:types,type',
        ]);

        Type::create($validatedData);

        return redirect()->route('types.index')->with('success', 'Type added successfully');
    }

    // Show the form to edit the specified type
    public function edit(Type $type)
    {
        // Return the type data as JSON for editing
        return response()->json($type);
    }

    // Update the specified type in the database
    public function update(Request $request, Type $type)
    {
        $validatedData = $request->validate([
            'type' => 'required|string|max:255|unique:types,type',
        ]);

        // Update the type with the new data
        $type->update($validatedData);

        // Redirect back with a success message
        return redirect()->route('types.index')->with('success', 'Type updated successfully');
    }

    // Delete the specified type from the database
    public function destroy(Type $type)
    {
        // Delete the type
        $type->delete();

        // Redirect back with a success message
        return redirect()->route('types.index')->with('success', 'Type deleted successfully');
    }
}

