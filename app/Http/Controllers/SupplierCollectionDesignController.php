<?php 

namespace App\Http\Controllers;

use App\Models\SupplierCollectionDesign;
use App\Models\Supplier;
use App\Models\SupplierCollection;
use Illuminate\Http\Request;

class SupplierCollectionDesignController extends Controller
{
    // Display all supplier collection designs
    public function index()
    {
        $suppliers = Supplier::orderBy('id', 'desc')->get();
        $supplierCollectionDesigns = SupplierCollectionDesign::with('supplier', 'supplierCollection')->get();
        
        return view('admin.master.suppliercollectiondesign', compact('supplierCollectionDesigns','suppliers'));
    }

    // Show the form to create a new supplier collection design
    public function create()
    {
        // Fetch all suppliers and collections to pass to the view
        $suppliers = Supplier::all();
        $collections = SupplierCollection::all();

        return view('admin.master.suppliercollectiondesign_create', compact('suppliers', 'collections'));
    }

    // Store a new supplier collection design
    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id', // Ensure supplier exists
            'supplier_collection_id' => 'required|exists:supplier_collections,id', // Ensure supplier collection exists
            'design_name' => 'required|max:255',
        ]);

        // Create a new supplier collection design
        SupplierCollectionDesign::create([
            'supplier_id' => $request->supplier_id,
            'supplier_collection_id' => $request->supplier_collection_id,
            'design_name' => $request->design_name,
        ]);

        return redirect()->route('supplierCollectionDesigns.index')
                         ->with('success', 'Supplier Collection Design added successfully!');
    }

    // Show the form to edit an existing supplier collection design
    public function edit($id)
    {
        $supplierCollectionDesigns = SupplierCollectionDesign::with('supplier', 'supplierCollection')->findOrFail($id);
        return response()->json($supplierCollectionDesigns);
    }

    // Update an existing supplier collection design
    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id', // Ensure supplier exists
            'supplier_collection_id' => 'required|exists:supplier_collections,id', // Ensure supplier collection exists
            'design_name' => 'required|max:255|unique:supplier_collection_designs,design_name,' . $id, // Exclude the current record from the unique constraint
        ]);

        // Find the design and update it
        $design = SupplierCollectionDesign::findOrFail($id);
        $design->update([
            'supplier_id' => $request->supplier_id,
            'supplier_collection_id' => $request->supplier_collection_id,
            'design_name' => $request->design_name,
        ]);

        return redirect()->route('supplierCollectionDesigns.index')
                         ->with('success', 'Supplier Collection Design updated successfully!');
    }

    public function getSupplierCollections($supplierId)
    {
        // Ensure the supplier exists before querying collections
        $supplier = Supplier::find($supplierId);

        if (!$supplier) {
            return response()->json(['error' => 'Supplier not found'], 404);
        }

        // Get collections based on the supplier ID
        $collections = SupplierCollection::where('supplier_id', $supplierId)->get();

        // Return the collections as JSON
        return response()->json($collections);
    }

    public function getSupplierCollectionDesigns($supplierId, $collectionId)
    {
        $designs = SupplierCollectionDesign::where('supplier_id', $supplierId)
                                            ->where('supplier_collection_id', $collectionId)
                                            ->get();
        return response()->json($designs);
    }


    // Delete a supplier collection design
    public function destroy($id)
    {
        // Find the design and delete it
        $design = SupplierCollectionDesign::findOrFail($id);
        $design->delete();

        return redirect()->route('supplierCollectionDesigns.index')
                         ->with('success', 'Supplier Collection Design deleted successfully!');
    }
}
