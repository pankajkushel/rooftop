<?php

namespace App\Http\Controllers;

use App\Models\FranchiseTemp;
use Illuminate\Http\Request;
use App\Mail\FranchiseInformationMail;
use App\Mail\FranchiseRegistrationMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\MasterCity;
use App\Models\Franchise;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class FranchiseTempController extends Controller
{
    protected $whatsAppService;
    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }
    
    private function generateNextCode($lastCode, $prefix)
    {
        if (!$lastCode) {
            return $prefix . "0001";
        }
        $numberPart = (int) substr($lastCode, strlen($prefix));
        $nextNumber = str_pad($numberPart + 1, 6, "0", STR_PAD_LEFT);
        return $prefix . $nextNumber;
    }

    public function store(Request $request)
    {   
        $rules = [
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:users,email",
            "mobile" => "required|string|max:20",
            "alt_mobile" => "nullable|string|max:20|different:mobile",
            "address" => "required|string|max:255",
            'registerationType' => 'required|in:Individual,Company,proprietor',
            'company_name' => 'nullable|required_if:registerationType,Company,proprietor|string|max:255',
            'employees' => 'nullable|required_if:registerationType,Company,proprietor|integer|min:1',

            "pincode" => "required|integer",
            "city" => "required|string|max:255",
            "state" => "required|string|max:255",
            "country" => "required|string|max:255",
        ];

        $messages = [
            "email.email" => "Please enter a valid email address.",
            "email.unique" => "The email has already been taken.",
            "pincode.integer" => "Pincode must be a number.",
        ];

        // Perform validation
        $validator = Validator::make($request->all(), $rules, $messages);
        // dd($request->all());
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->with("errors", $validator->errors())->withFragment('registerWithUs');
        }

        try {
            $data = FranchiseTemp::create($request->all());
            if (!empty($request->email)) {
                 Mail::to($request->email)->send(new FranchiseInformationMail($request->all()));
            }

            // send whatsaap Message
            $parameters = [
                [
                    'type' => 'text',
                    'text' => $request->name
                ]
            ];

            $this->whatsAppService->sendMessage('91'.$request->mobile, 'newfranchi', $parameters);
            // end send whatsaap Message

            return redirect()
                ->back()
                ->with("success", "We will get back to you shortly.")->withFragment('registerWithUs');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with("errors", "Something Went Wrong!")->withFragment('registerWithUs');
        }
    }

    public function store_admin(Request $request)
    {
        // echo '<pre>';
        // print_r($request->all()); exit;
        $rules = [
            "name" => "required|string|max:255",
            "email" => "required|email|max:255|unique:users,email",
            "mobile" => "required|string|max:20",
            "alt_mobile" => "nullable|string|max:20|different:mobile",
            "address" => "required|string|max:255",
            'registerationType' => 'required|in:Individual,Company,Proprietor',
            'company_name' => 'nullable|required_if:registerationType,Company,Proprietor|string|max:255',
            'employees' => 'nullable|required_if:registerationType,Company,Proprietor|integer|min:1',

            "pincode" => "required|integer",
            "city" => "required|string|max:255",
            "state" => "required|string|max:255",
            "country" => "required|string|max:255",
        ];

        $messages = [
            "email.email" => "Please enter a valid email address.",
            "email.unique" => "The email has already been taken.",
            "pincode.integer" => "Pincode must be a number.",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->with("errors", $validator->errors());
        }

        try {
            $data = FranchiseTemp::create($request->all());
            if (!empty($request->email)) {
                 Mail::to($request->email)->send(new FranchiseInformationMail($request->all()));
            }

            // send whatsaap Message
            $parameters = [
                [
                    'type' => 'text',
                    'text' => $request->name
                ]
            ];
            $this->whatsAppService->sendMessage('91'.$request->mobile, 'newfranchi', $parameters);
            // end send whatsaap Message

            return redirect()
                ->back()
                ->with("success", "We will get back to you shortly.");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with("errors", "Something Went Wrong!");
        }
    }

    public function index()
    {
        $franchiseTemps = FranchiseTemp::whereIn("status", [
            "pending",
            "reject",
        ])
            ->orderBy("id", "desc")
            ->get()
            ->groupBy("status");

        $franchises = Franchise::with("user")->orderBy('id', 'desc')->get();
        $cityStateData = MasterCity::orderBy('state_name')->orderBy('city_name')->get();
        $groupedCityStateData = $cityStateData->groupBy("state_name");

        return view("admin.franchise.approval", [
            "franchiseTempsPending" => $franchiseTemps->get(
                "pending",
                collect()
            ),
            "franchiseTempsReject" => $franchiseTemps->get("reject", collect()),
            "franchises" => $franchises,
            "groupedCityStateData" => $groupedCityStateData,
        ]);
    }

    public function generateSecurePassword($length = 8)
    {
        $uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $lowercase = "abcdefghijklmnopqrstuvwxyz";
        $numbers = "0123456789";
        $specialChars = '!@#$%^&*()_+-=[]{}|;:,.<>?';

        // Create the password with at least one of each type
        $password = [
            $uppercase[random_int(0, strlen($uppercase) - 1)],
            $lowercase[random_int(0, strlen($lowercase) - 1)],
            $numbers[random_int(0, strlen($numbers) - 1)],
            $specialChars[random_int(0, strlen($specialChars) - 1)],
        ];

        // Fill the rest of the password length with random characters
        $allChars = $uppercase . $lowercase . $numbers . $specialChars;
        for ($i = 4; $i < $length; $i++) {
            $password[] = $allChars[random_int(0, strlen($allChars) - 1)];
        }
        // Shuffle the password array to ensure randomness
        shuffle($password);

        return implode("", $password);
    }

    public function approve($id)
    {
        $franchiseTemp = FranchiseTemp::findOrFail($id);
        // Check if the email already exists in the 'users' table
        $existingUser = User::where("email", $franchiseTemp->email)->first();
        if ($existingUser) {
            return redirect()
                ->back()
                ->with("error", "A user with this email already exists.");
        }

        $password = $this->generateSecurePassword(8);

        $user = User::create([
            "name" => $franchiseTemp->name,

            "email" => $franchiseTemp->email,

            "password" => Hash::make($password), // Use a secure method for passwords
        ]);

        // Assign franchise role to the user

        $user->assignRole("Vendor");

        // Save additional franchise data

        $lastFranchiseId = Franchise::max("franchise_id");
        $nextFranchiseId = $this->generateNextCode($lastFranchiseId, "FR");

        Franchise::create([
            "user_id" => $user->id,

            "franchise_id" => $nextFranchiseId,

            "name" => $franchiseTemp->name ?? "",

            "email" => $franchiseTemp->email ?? "",

            "alt_mobile" => $franchiseTemp->alt_mobile ?? "",

            "employees" => $franchiseTemp->employees ?? "",

            "registerationType" => $franchiseTemp->registerationType ?? "",

            "company_name" => $franchiseTemp->company_name ?? "",

            "address" => $franchiseTemp->address,

            "pincode" => $franchiseTemp->pincode,

            "city" => $franchiseTemp->city,

            "state" => $franchiseTemp->state,

            "country" => $franchiseTemp->country,

            "mobile" => $franchiseTemp->mobile,
        ]);

        $franchiseTemp->delete();

        // Send email notification

        $data = [
            "name" => $franchiseTemp->name,

            "email" => $franchiseTemp->email,

            "password" => $password,
            "franchise_id" => $nextFranchiseId,
        ];

        Mail::to($franchiseTemp->email)->send(
            new FranchiseRegistrationMail($data)
        );

         // send whatsaap Message
         $parameters = [
            [
                'type' => 'text',
                'text' => $franchiseTemp->name
            ],
            [
                'type' => 'text',
                'text' => $franchiseTemp->email
            ],
            [
                'type' => 'text',
                'text' => $password
            ]
        ];

        $this->whatsAppService->sendMessage('91'.$franchiseTemp->mobile, 'confirmedfranchisess', $parameters);
        // end send whatsaap Message


        return redirect()
            ->back()
            ->with(
                "success",
                "Franchise approved and user created successfully."
            );
    }

    public function reject($id)
    {
        $franchiseTemp = FranchiseTemp::findOrFail($id);

        // Update the status to 'reject'

        $franchiseTemp->status = "reject";

        // Save the changes

        $franchiseTemp->save();

        return redirect()
            ->back()
            ->with("success", "Franchise rejected successfully.");
    }

    public function getFranchiseDetails($id, $type)
    {
        if ($type == "confirm") {
            $franchise = Franchise::with("user")->find($id);
        } else {
            $franchise = FranchiseTemp::find($id);
        }

        if ($franchise) {
            return response()->json([
                "status" => "success",

                "data" => $franchise,
            ]);
        } else {
            return response()->json([
                "status" => "error",

                "message" => "Franchise not found",
            ]);
        }
    }

    public function frontend_view()
    {

        $cityStateData = MasterCity::orderBy('state_name')->orderBy('city_name')->get();
        $groupedCityStateData = $cityStateData->groupBy("state_name");
        
        return view("frontend.franchise_reg", compact("groupedCityStateData"));
    }
}
