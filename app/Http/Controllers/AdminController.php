<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Franchise;
use App\Models\Product;
use App\Models\Appointment;
use App\Models\FranchiseTemp;
use App\Models\Quotation;
use App\Models\User;
use App\Models\ZipCode;
use Illuminate\Support\Facades\Auth;



class AdminController extends Controller
{
    public function dashboard(Request $request){
        $roles= Auth::user()->getRoleNames();
        
        $userRole = $roles->isNotEmpty() ? $roles[0] : null;
        $user = Auth::user();
      
         
        if(!empty($user)){
            $franchiseID = Franchise::where('user_id',$user->id)->first();
        }
        
        $product=Product::all();
        $appointment = Appointment::where('status', "!=" ,"0")->orderBy('created_at', 'desc');

        if ($request->has('dateFilter')) {
            $appointment->whereDate('created_at', $request->dateFilter);
        }
        if($userRole == "Vendor"){
            $appointment->where('id',$franchiseID->id);
        }
        $appointmentCount = $appointment->count();
        $appointment = $appointment->get();


        $quotations = Quotation::with('appointment')->orderBy('created_at', 'desc');
        if($userRole == "Vendor"){
            $quotations->where('id',$franchiseID->id);
        }
        $quotationCount = $quotations->count();
        $quotations = $quotations->get();
        $user=User::all();

        $franchiseQuery = new Franchise;
        $franchiseTemp = new FranchiseTemp;
        $total_franchise = 0;
        if($userRole == 'Vendor'){
            $franchise = $franchiseQuery->where('franchise_id', $franchiseID)->get();
            $franchiseCount = $franchise->count();
            $total_franchise = $franchiseCount;
        }else{
            $franchise = $franchiseQuery->all();
            $franchiseCount = $franchiseQuery->count();

            $franchiseTempCount = $franchiseTemp->count(); 

            $total_franchise =   $franchiseCount + $franchiseTempCount;
        }
       
        return view('admin.dashboard',compact('franchise','total_franchise','product','appointment','appointmentCount','user','quotations','quotationCount'));
    }

    public function getLocationByPincode(Request $request)
    {
        $pincode = $request->input('pincode');
        $data = ZipCode::where('zip_code', $pincode)->first();
        if ($data) {
            // Assuming the 'location' is a field you want to return
            return response()->json(['status'=> 'true','location_data' => $data,'message' => 'success']);
        }else{
            return response()->json(['status'=> 'false','location_data' => '','message' => 'failed']);
        }
    }

    public function quotation_list()
    {
        // Fetch appointments with status 'Pending'
        $appointments = Appointment::where('status', '!=', "0")->orderBy('created_at', 'desc')->get();
        
        $statusCounts = $appointments->groupBy('status')->map(function ($appointments) {
            return $appointments->count();
        });

        
        // Now you can access the counts like so:
        $pendingCount = $statusCounts->get('Appointment Booked', 0);  
        $assignedCount = $statusCounts->get('Vendor Assigned', 0);
        $completedCount = $statusCounts->get('Vendor Completed', 0);
        $rejectedCount = $statusCounts->get('Vendor Rejected', 0);

        // Fetch appointments with status 'Assigned'
        $assignedAppointments = Appointment::join('franchises','appointments.franchise_id','=','franchises.id')->join('users','users.id','=','franchises.user_id')->select('appointments.*','users.name as franchise_name')->where('appointments.status', 'Franchise Assigned')->get();
        $franchises=Franchise::orderby('id','desc')->get();
        
        return view('admin.quotations', compact('appointments','pendingCount','assignedCount','completedCount','rejectedCount', 'assignedAppointments','franchises'));
    }

    public function getQuotationsData(Request $request)
    {
        // Retrieve the 'status' input from the request, default to 'pending' if not set
        $status = $request->input('status', 'pending');

        // Use a switch-case for better readability and easier status mapping
        switch ($status) {
            case 'pending':
                $status = 'Appointment Booked';
                break;
            case 'complete':
                $status = 'Vendor Completed';
                break;
            default:
                $status = 'Appointment Booked';
                break;
        }

        // Fetch appointments based on the mapped status
        $quotations = Appointment::where('status','=', $status)->orderBy('created_at', 'desc')->get();

        // Return the data as JSON response
        return response()->json(['data' => $quotations]);
    }

    public function calculator(){
        return view('admin.calculator');
    }
}
