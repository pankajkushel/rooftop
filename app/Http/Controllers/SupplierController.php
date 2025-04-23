<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierCollection;
use App\Models\SupplierCollectionDesign;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');  // Add authentication middleware if required
    }

    // Display a listing of the suppliers
    public function index()
    {
        $suppliers = Supplier::orderBy('id', 'desc')->get();  // Retrieve all suppliers
        return view('admin.master.suppliername', compact('suppliers'));
    }

    // Show the form for creating a new supplier
    public function create()
    {
        return view('admin.suppliers.create');
    }

    // Store a newly created supplier in the database
    public function store(Request $request)
    {
        $request->validate([
            'supplier_name' => 'required|string|max:255|unique:suppliers,name',
        ]);

        Supplier::create([
            'name' => $request->supplier_name,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier added successfully.');
    }


    // Show the form for editing a supplier
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);  // Find the supplier by ID
        return response()->json($supplier);
    }

    public function collections($supplierId){
        $supplier = SupplierCollection::where('supplier_id',$supplierId)->get();  // Find the supplier by ID
        
        return response()->json($supplier);
    }
    
    // Update the specified supplier in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_name' => 'required|string|max:255|unique:suppliers,name',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'name' => $request->supplier_name,
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    // Remove the specified supplier from the database
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
