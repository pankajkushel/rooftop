<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    public function index()
    {
        $productTypes = ProductType::orderBy('id', 'desc')->get();
        return view('admin.master.producttype', compact('productTypes'));
    }

    public function create()
    {
        return view('admin.master.producttype');
    }

    public function store(Request $request)
    {
        // $product_unit = implode(',',$request->product_unit);
        $request->validate([
            'product_type' => 'required|string|max:255|unique:product_types,product_type',
            // 'product_unit' => 'required|string|max:255',
        ]);

        $product_unit = is_array($request->product_unit) ? implode(',', $request->product_unit) : $request->product_unit;

        ProductType::create([
            'product_type' => $request->product_type,
            'product_unit' => $product_unit
        ]);

        return redirect()->route('product-types.index')->with('success', 'Product Type added successfully.');
    }


    public function edit(ProductType $productType)
    {
        return response()->json($productType);
    }

    public function update(Request $request, ProductType $productType)
    {
        $product_unit = is_array($request->product_unit) ? implode(',', $request->product_unit) : $request->product_unit;

        $productType->update([
            'product_type' => $request->product_type,
            'product_unit' => $product_unit
        ]);

        return redirect()->route('product-types.index')->with('success', 'Product Type updated successfully.');
    }

    public function destroy(ProductType $productType)
    {
        $productType->delete();

        return redirect()->route('product-types.index')->with('success', 'Product Type deleted successfully.');
    }

    public function getProductTypes(){
        $productTypes = ProductType::all();

        return $productTypes;
    }

    public function getProductAll(){
        $productData = Product::with('ProductType')->where('design_sku', '!=' ,'NULL')
        ->distinct()
        ->get();

        return $productData;
    }
}
