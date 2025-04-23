<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\ZipCode;
use App\Mail\AppointmentSuccessMail;
use App\Mail\QueryBookedMail;
use App\Mail\AppointmentScheduleMail;
use App\Mail\AppointmentRescheduleMail;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use App\Models\Franchise;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Exports\BookQueryExport;
use App\Services\WhatsAppService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rules\Unique;
use Rinvex\Country\CountryLoader;
use Illuminate\Support\Collection;

class AppointmentController extends Controller
{
    protected $whatsAppService;
    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    private function generateNextCode($lastCode, $prefix)
    {
        if (!$lastCode) {
            return $prefix . "000001";
        }
        $numberPart = (int) substr($lastCode, strlen($prefix));
        $nextNumber = str_pad($numberPart + 1, 6, "0", STR_PAD_LEFT);
        return $prefix . $nextNumber;
    }

    public function index()
    {
        $userRole = Auth::user()->getRoleNames()[0];
        $user = Auth::user();
        // $countries = countries();
        $countries = collect(countries());
        // dd($countries->first());
        $india = $countries->where('iso_3166_1_alpha2', 'IN')->first();
        // $india = $countries->where('cca2', 'IN')->first();
       //    dd($india);
        if (!$india) {
            return response()->json(['error' => 'India not found in country list'], 500);
        }
        // $states = $india['subdivisions'];
        // dd($states);
        
        $appointments = collect(); 

        $appointmentsQuery = Appointment::with('franchise')->where("status", "!=", "0");

        if ($userRole == "Vendor") {
            $franchise = Franchise::where("user_id", $user->id)->first();
            if ($franchise) {
                $appointments = $appointmentsQuery
                    ->where("franchise_id", $franchise->id)
                    ->get();
                $statusCounts = $appointments->groupBy("status")->map->count();

                $pendingCount = $statusCounts->get("2", 0);
                $completedCount = $statusCounts->get("4", 0);
                $holdCount = $statusCounts->get("3", 0);
                $assignedCount = $rejectedCount = 0;
            } else {
                $pendingCount = $completedCount = $holdCount = $assignedCount = $rejectedCount = 0;
            }
        } elseif (in_array($userRole, ["Admin", "Super Admin","Help Desk"])) {
            $appointments = $appointmentsQuery->get();
            $statusCounts = $appointments->groupBy("status")->map->count();

            // Access counts for all statuses
            $pendingCount = $statusCounts->get("1", 0);
            $assignedCount = $statusCounts->get("2", 0);
            $completedCount = $statusCounts->get("4", 0);
            $holdCount = $statusCounts->get("3", 0);
        }

        $assignedAppointments = Appointment::join(
            "franchises",
            "appointments.franchise_id",
            "=",
            "franchises.id"
        )
            ->join("users", "users.id", "=", "franchises.user_id")
            ->select("appointments.*", "users.name as franchise_name")
            ->where("appointments.status", "2")
            ->with('franchise')
            ->get();
            

        $franchises = Franchise::orderBy("id", "desc")->get();

        return view(
            "admin.appointments",
            compact(
                "appointments",
                "pendingCount",
                "assignedCount",
                "completedCount",
                "holdCount",
                "assignedAppointments",
                "franchises"
            )
        );
    }


    public function querybooked()
    {
        // Fetch appointments with status 'Pending'
        $appointments = Appointment::where("status", "=", "0")->get();

        $statusCounts = $appointments
            ->groupBy("status")
            ->map(function ($appointments) {
                return $appointments->count();
            });

        // Now you can access the counts like so:
        $pendingCount = $statusCounts->get("0", 0); // Default to 0 if no 'pending' status found
        $assignedCount = $statusCounts->get("2", 0);
        $completedCount = $statusCounts->get("4", 0);
        $holdCount = $statusCounts->get("3", 0);

        $assignedAppointments = Appointment::join(
            "franchises",
            "appointments.franchise_id",
            "=",
            "franchises.id"
        )
            ->join("users", "users.id", "=", "franchises.user_id")
            ->select("appointments.*", "users.name as franchise_name")
            ->where("appointments.status", "2")
            ->get();
        $franchises = Franchise::orderby("id", "desc")->get();

        return view(
            "admin.query-booked",
            compact(
                "appointments",
                "pendingCount",
                "assignedCount",
                "completedCount",
                "holdCount",
                "assignedAppointments",
                "franchises"
            )
        );
    }

    public function getAppointmentData(Request $request)
    {
        // Define status mapping
        $statusMap = [
            "pending" => "1",
            "assign" => "2",
            "complete" => "4",
            "hold" => "3",
        ];

        $status = $request->input("status", "1");
        $status = $statusMap[$status] ?? "1";
        $franchises = [];

        // Check if the user is a Franchise or Admin/Super Admin
        $userRole = Auth::user()->getRoleNames()[0];

        if ($userRole === "Vendor") {
            // Fetch Franchise-specific appointments
            $statusMap = [
                "pending" => "2",
                "complete" => "4",
                "hold" => "3",
            ];

            $status = $request->input("status");
            $status = $statusMap[$status] ?? "2";
            $franchiseDetail = Franchise::where(
                "user_id",
                Auth::user()->id
            )->first();
            if ($franchiseDetail) {
                $franchises = Appointment::where("status", $status)
                    ->where("franchise_id", $franchiseDetail->id)
                    ->with('franchise')
                    ->orderBy('id', 'desc')
                    ->get()
                    ->toArray();
            }
        } elseif (in_array($userRole, ["Admin", "Super Admin","Help Desk"])) {
            $franchises = Appointment::where("status", $status)
                ->with('franchise')
                ->orderBy('id', 'desc')
                ->get()
                ->toArray();
        }

        // Return the data as a JSON response
        return response()->json([
            "data" => $franchises,
            "role" => Auth::user()->getRoleNames()[0] ?? "",
        ]);
    }

    public function store(Request $request)
    {
        $zipCodes = ZipCode::pluck("zip_code")->toArray();
        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email",
            "mobile" => "required|digits_between:10,15",
            "address" => "required|string|max:255",
            "pincode" => "required|digits:6",
            "city" => "required|string|max:100",
            "state" => "required|string|max:100",
            "country" => "required|string|max:100",
        ]);

        if (in_array($validatedData["pincode"], $zipCodes)) {
            $validatedData["status"] = "1";
            $responseMessage = "Appointment created successfully!";
        } else {
            $validatedData["status"] = "0";
            $responseMessage = "Query created successfully!";
        }

        $lastAppointmentId = Appointment::max("uniqueid");
        $nextAppointmentId = $this->generateNextCode($lastAppointmentId, "AP");

        $validatedData["uniqueid"] = $nextAppointmentId;

        $appointment = Appointment::create($validatedData);

        if($validatedData["status"] == 0){

            // send whatsaap Message
            $parameters = [
                
            ];

            $this->whatsAppService->sendMessageWp('91'.$request->mobile, 'triggersforcustomerservicingtheirarea');
            // end send whatsaap Message



            Mail::to($appointment->email)->send(
                new QueryBookedMail($appointment)
            );
        }else{
            // send whatsaap Message
            $parameters = [
                [
                    'type' => 'text',
                    'text' => $request->name
                ]
            ];

            $this->whatsAppService->sendMessage('91'.$request->mobile, 'thankyour', $parameters);
            // end send whatsaap Message

            Mail::to($appointment->email)->send(
                new AppointmentSuccessMail($appointment)
            );
        }
        // Send success email
        

        return response()->json(["message" => $responseMessage,'status_check' => $validatedData["status"]], 201);
    }

    public function assign(Request $request)
    {
        // Validate the input fields
        $request->validate([
            "appointment_id" => "required|exists:appointments,id",
            "franchise_id" => "required|exists:franchises,id",
            "dateFilter" => "required|date|after_or_equal:today",
        ]);

        // Find the appointment by ID
        $appointment = Appointment::findOrFail($request->appointment_id);

        // Convert the date to a Carbon instance (if it's not already)
        $appointment->appointment_date = Carbon::parse($request->dateFilter); // Convert the string to Carbon

        // Update the franchise and status
        $appointment->franchise_id = $request->franchise_id;
        $appointment->remarks = $request->remarks;
        $appointment->status = "2"; // Update the status

        // Save the appointment
        $appointment->save();
        $franchiseDetail = Franchise::find($request->franchise_id);
        $franchiseName = $franchiseDetail ? $franchiseDetail->name : 'N/A';
        $franchiseEmail = $franchiseDetail ? $franchiseDetail->email : 'N/A';
        $appointmentDate = Carbon::parse($appointment->appointment_date)->format('d/m/Y');
        $appointmentTime = Carbon::parse($appointment->appointment_date)->format('H.i').'hrs';


        // send whatsaap Message
        $parameters = [
            [
                'type' => 'text',
                'text' => $franchiseName
            ],
            [
                'type' => 'text',
                'text' => $appointmentDate .' '.$appointmentTime
            ]
        ];

        $this->whatsAppService->sendMessage('91'.$franchiseDetail->mobile, 'appointmentscheduled', $parameters); // send to franchise
        $this->whatsAppService->sendMessage('91'.$appointment->mobile, 'appointmentscheduled', $parameters); // send to customer
        // end send whatsaap Message

        // Pass these to the email
        Mail::to($appointment->email)->cc($franchiseEmail)->send(
            new AppointmentScheduleMail($appointment, $appointmentDate, $appointmentTime, $franchiseName)
        );
        

        // Redirect back with success message
        return redirect()
            ->back()
            ->with("success", "Vendor assigned successfully.");
    }

    public function reassign(Request $request)
    {
        // Validate the input fields
        //dd($request->all());
        $request->validate([
            "appointment_id1" => "required|exists:appointments,id",
            "dateFilter1" => "required|date|after_or_equal:today",
            "franchise_id1" => "required|exists:franchises,id",
        ]);

        // Find the appointment by ID
        
        $appointment = Appointment::findOrFail($request->appointment_id1);

        // Convert the date to a Carbon instance (if it's not already)
        $appointment->appointment_date = Carbon::parse($request->dateFilter1); // Convert the string to Carbon

        // Update the franchise and status
        $appointment->franchise_id = $request->franchise_id1;
        $appointment->remarks = $request->remarks1;
        $appointment->status = "2"; // Update the status

        // Save the appointment
        $appointment->save();
        $franchiseDetail = Franchise::find($request->franchise_id1);
        $franchiseName = $franchiseDetail ? $franchiseDetail->name : 'N/A';
        $franchiseEmail = $franchiseDetail ? $franchiseDetail->email : 'N/A';
        $appointmentDate = Carbon::parse($appointment->appointment_date)->format('d/m/Y');
        $appointmentTime = Carbon::parse($appointment->appointment_date)->format('H.i').'hrs';


        // send whatsaap Message
        $parameters = [
            [
                'type' => 'text',
                'text' => $franchiseName
            ],
            [
                'type' => 'text',
                'text' => $appointmentDate .' '.$appointmentTime
            ]
        ];

        $this->whatsAppService->sendMessage('91'.$franchiseDetail->mobile, 'rescheduledappointment', $parameters);  // send to franchise
        $this->whatsAppService->sendMessage('91'.$appointment->mobile, 'rescheduledappointment', $parameters); // send to customer
        // end send whatsaap Message

        // Send the success email
        Mail::to($appointment->email)->cc($franchiseEmail)->send(
            new AppointmentRescheduleMail($appointment, $appointmentDate, $appointmentTime, $franchiseName)
        );

        // Redirect back with success message
        return redirect()
            ->back()
            ->with("success", "Vendor Re-assigned successfully.");
    }

    public function getAppointmentDetails($id, $type=null)
    {
		 //echo $type;die; 
        // Fetch the appointment by ID
        $appointment = Appointment::findOrFail($id);

        // Use a switch-case for better readability and easier status mapping
        switch ($type) {
            case "pending":
                $status = 1;
                break;
            case "assign":
                $status = 2;
                break;
            case "hold":
                $status = 3;
                break;
            case "complete":
                $status = 4;
                break;
            default:
                $status = 0; // Default status
                break;
        }

        // Check if the appointment's status matches the type passed
        if ($appointment && $appointment->status == $status) {
            return response()->json([
                "status" => "success",
                "data" => $appointment,
                "message" => "Appointment details fetched successfully.",
            ]);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "Appointment not found or status mismatch.",
            ]);
        }
    }

    public function reject($id)
    {
        $appointData = Appointment::findOrFail($id);

        // Update the status to 'reject'
        $appointData->status = "Vendors Rejected";

        // Save the changes
        $appointData->save();

        return redirect()
            ->back()
            ->with("success", "Appointment rejected successfully.");
    }

    public function exportBookQuery()
    {
        return Excel::download(new BookQueryExport, 'book_query.xlsx');
    }

    public function getFranchiseList($apnt_id){
        $franchises = Appointment::with('local_franchise')
        ->findOrFail($apnt_id);
        return $franchises;
    }
}
