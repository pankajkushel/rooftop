<?php

namespace App\Http\Controllers\Assigned;

use App\Http\Controllers\Controller;
use App\Models\Assigned\WorkAssigned;
use Illuminate\Http\Request;

class AssignedController extends Controller
{
     public function WorkAssign(){
        $workAssigned  = WorkAssigned::all();
        return view('WorkAssigned.assigned',compact('workAssigned'));
     }



    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:15',
            'description' => 'nullable|string',
            'assigned_date' => 'required|date',
            'deadline' => 'nullable|date',
            'completion_date' => 'nullable|date',
        ]);
    
        // Data save 
        WorkAssigned::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'task_title'=>$request->task_title,

            'task_description' => $request->description,
            'assigned_date' => $request->assigned_date,
            'deadline' => $request->Dead_Line,
            'completion_date' => $request->CompletionDate,
        ]);
    
        return redirect()->back()->with('success', 'Work assigned successfully.');
    }
}
