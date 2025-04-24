<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\Controller;
use App\Models\Lead\LeadModel;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class LeadController extends Controller
{
     public function LeadIndex(){

        $leads = LeadModel::latest()->get();
        $totalLeads = LeadModel::count();
        // dd($totalLeads);
        return view('Lead.lead',compact('leads',
            'totalLeads'
        ));
     }
     public function LeadCreate(Request $request)
     {
         // Validate only necessary fields
         $validated = $request->validate([
            
                'full_name'     => 'required|string|max:255',
                'email'         => 'nullable|email|unique:lead_models,email',
                'phone'         => 'required|digits:10|unique:lead_models,phone',
                'source'        => 'nullable|string|max:100',
                'assigned_to'   => 'nullable|exists:users,id',
                'notes'         => 'nullable|string',
                'address'       => 'nullable|string|max:255',
         ]);
           
         $lead = new LeadModel();
         $lead->full_name     = $request->full_name;
         $lead->email         = $request->email;
         $lead->phone         = $request->phone;
         $lead->source        = $request->source;
         $lead->assigned_to   = $request->assigned_to;
         $lead->created_by    = Auth::id(); // 👈 This is the fix

         $lead->address       = $request->address;

          $lead->save();

         
     
         return redirect()->route('leadIndex')->with('success','Your Lead is created Successfully');
             
     }


     public function LeadEdit($id)
     {
         $lead = LeadModel::findOrFail($id);
      
        return view('Lead.leadEdit', compact('lead'));
     }



     public function LeadUpdate(Request $request, $id)
     {
         $lead = LeadModel::findOrFail($id);
 
         $validated = $request->validate([
             'full_name'     => 'required|string|max:255',
             'email'         => 'nullable|email|unique:lead_models,email,' . $lead->id,
             'phone'         => 'required|digits:10|unique:lead_models,phone,' . $lead->id,
             'source'        => 'nullable|string|max:100',
             'assigned_to'   => 'nullable|exists:users,id',
             'notes'         => 'nullable|string',
             'address'       => 'nullable|string|max:255',
         ]);
 
         $lead->full_name   = $request->full_name;
         $lead->email       = $request->email;
         $lead->phone       = $request->phone;
         $lead->source      = $request->source;
         $lead->assigned_to = $request->assigned_to;
         $lead->status    = $request->status;
         $lead->address     = $request->address;
        
         $lead->save();
 
         return redirect()->route('leadIndex')->with('success', 'Lead updated successfully!');
     }
     
// delete api
     public function LeadDelete($id)
     {
         $lead = LeadModel::findOrFail($id);
         $lead->delete();
 
         return redirect()->route('leadIndex')->with('success', 'Lead deleted successfully!');
     }

   }
  

