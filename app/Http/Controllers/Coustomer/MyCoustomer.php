<?php

namespace App\Http\Controllers\Coustomer;

use App\Http\Controllers\Controller;
use App\Models\Coustomer\Coustomer;
use App\Models\Lead\LeadModel;
use App\Models\User;
use Illuminate\Http\Request;

class MyCoustomer extends Controller
{
    public function Coustomer(){
        $coustomer = User::where('is_customer',true)->get();
       
        return view('Coustomer.coustomer',compact('coustomer'));
    }
       
    public function CoustomerEdit($id)
    {
        $coustomer = Coustomer::findOrFail($id);
     
       return view('Coustomer.coustomerEdit', compact('coustomer'));
    }


    public function CoustomerUpdate(Request $request,$id)
    {
        $costomer = Coustomer::findOrFail($id);
        $request->validate([
               'address' => 'nullable|string|max:500',
               'gender' => 'required|in:Male,Female,Other',
               'dob' => 'required|date|before:today',                          
        ]);

        $costomer->address  = $request->address;
        $costomer->gender   = $request->gender;
        $costomer->dob      = $request->dob;

        $costomer->save();

         
        return redirect()->route('coustomer', $id)->with('success', 'Customer updated successfully.');
            
    }
}
