<?php

namespace App\Http\Controllers;

use App\Models\DesignType;
use Illuminate\Http\Request;

class DesignTypeController extends Controller
{
    // Display a listing of the resource
    public function index()
    {
        $designTypes = DesignType::orderBy('id', 'desc')->get();
        return view('admin.master.designtype', compact('designTypes'));
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $request->validate([
            'design_type' => 'required|string|max:255|unique:design_types,design_type',
        ]);

        DesignType::create(['design_type' => $request->design_type]);

        return redirect()->route('design-types.index')->with('success', 'Design type added successfully.');
    }

    // Show the form for editing the specified resource
    public function edit(DesignType $designType)
    {
        return response()->json($designType);
    }

    // Update the specified resource in storage
    public function update(Request $request, DesignType $designType)
    {
        $request->validate([
            'design_type' => 'required|string|max:255|unique:design_types,design_type,' . $designType->id,
        ]);

        $designType->update(['design_type' => $request->design_type]);

        return redirect()->route('design-types.index')->with('success', 'Design type updated successfully.');
    }

    // Remove the specified resource from storage
    public function destroy(DesignType $designType)
    {
        $designType->delete();

        return redirect()->route('design-types.index')->with('success', 'Design type deleted successfully.');
    }
}
