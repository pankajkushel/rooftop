<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class FranchiseTeamController extends Controller
{
    // List team members of the logged-in franchise
    public function index()
    {
        $franchise = auth()->user();
        $teamMembers = User::where('franchise_id', $franchise->id)->orderBy('id', 'desc')->get();

        return view('franchise.team.index', compact('teamMembers'));
    }

    // Show form to create a team member
    public function create()
    {
        return view('franchise.team.create');
    }

    // Store a new franchise team member
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $franchise = auth()->user(); // Get the logged-in franchise user

        // Create the team member
        $teamMember = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'franchise_id' => $franchise->id, // Assign to the franchise
        ]);

        // Assign role as Franchise Team Member
        $teamMember->assignRole('Franchise Team Member');

        return redirect()->route('franchise.team.index')->with('success', 'Team member added successfully.');
    }
}

