<?php

namespace App\Http\Controllers;

use App\Models\ZipCode;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ZipCodesImport;
use App\Exports\ZipCodesExport;

class ZipCodeController extends Controller
{
    public function index()
    {
        $zipCodes = ZipCode::orderBy('id', 'desc')->get();
        return view('admin.master.zipcode', compact('zipCodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|numeric|unique:zip_codes,zip_code',
        ]);

        ZipCode::create($request->all());
        return redirect()->route('zipcodes.index')->with('success', 'Zip Code added successfully.');
    }


    public function edit($id)
    {
        $zipCode = ZipCode::findOrFail($id);
        return response()->json($zipCode);
    }

    public function update(Request $request, $id)
    {
        $zipCode = ZipCode::findOrFail($id);

        $request->validate([
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|numeric|unique:zip_codes,zip_code,' . $id,
        ]);

        $zipCode->update($request->all());
        return redirect()->route('zipcodes.index')->with('success', 'Zip Code updated successfully.');
    }

    public function destroy($id)
    {
        $zipCode = ZipCode::findOrFail($id);
        $zipCode->delete();

        return redirect()->route('zipcodes.index')->with('success', 'Zip Code deleted successfully.');
    }

    public function import(Request $request)
    {
        Excel::import(new ZipCodesImport, $request->file('file'));
        return back()->with('success', 'Zip Codes Imported Successfully!');
    }

    public function export()
    {
        return Excel::download(new ZipCodesExport, 'zipcodes.xlsx');
    }
}
