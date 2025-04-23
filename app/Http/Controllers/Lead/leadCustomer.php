<?php

namespace App\Http\Controllers\Lead;

use App\Http\Controllers\Controller;
use App\Models\Coustomer\Coustomer;
use App\Models\Lead\LeadModel;
use App\Models\User;
use App\Models\Customer; // Customer Model ko import karna hoga
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; 

class leadCustomer extends Controller
{

    



    public function convert($id)
    {
        // Lead ko find karo
        $lead = LeadModel::findOrFail($id);
    
        // Check karo agar lead already converted hai to return karo
        if ($lead->is_converted) {
            return back()->with('error', 'Already converted.');
        }

        // Email generate karo, agar email nahi hai to
        $email = $lead->email ?? 'user' . $lead->id . '@example.com';

        // Check karo agar email already exist karta hai
        if (User::where('email', $email)->exists()) {
            return back()->with('error', 'This email already exists in users. Cannot convert.');
        }

        // Password generate karo
        $password = Str::random(8);

      
        $user = User::create([
            'name' => $lead->full_name, 
            'email' => $email,  // Lead ka email
            'password' => Hash::make($password),  
            'is_customer' => true, 
        ]);
        $user->assignRole('Customer Login'); 

       
        if ($lead->email) {
            Mail::raw("Welcome {$lead->name}, Your login ID: {$email}, Password: {$password}", function($message) use ($email) {
                $message->to($email)->subject('Your Login Details');
            });
        }

        // Lead ko convert karne ke baad update karo
        $lead->is_converted = true;
        $lead->save();

        return back()->with('success', 'Lead converted to customer successfully.');
    }

    public function customerList()
    {
        $customerdata = LeadModel::join('users', 'lead_models.email', '=', 'users.email')
                         ->select('lead_models.*', 'users.id as user_id', 'users.name as user_name')
                         ->get();
    
        return view('Coustomer.coustomer', compact('customerdata'));
    }
    


}
