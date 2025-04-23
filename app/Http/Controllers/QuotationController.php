<?php



namespace App\Http\Controllers;



use App\Models\Appointment;

use App\Models\Franchise;

use App\Models\ProductType;

use App\Models\Quotation;

use App\Models\QuotationItem;

use App\Mail\QuotationGeneratedMail;

use App\Models\Product;

use App\Models\QuotationSection;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;

use App\Services\WhatsAppService;



class QuotationController extends Controller

{

    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)

    {

        $this->whatsAppService = $whatsAppService;

    }



    private function generateNextCode($lastCode, $prefix)

    {

        if (!$lastCode) {

            return $prefix . "00001";

        }

        $numberPart = (int) substr($lastCode, strlen($prefix));

        $nextNumber = str_pad($numberPart + 1, 6, "0", STR_PAD_LEFT);

        return $prefix . $nextNumber;

    }



        public function index()

        {

            $user = Auth::user();

            $userRole = $user->getRoleNames()->first();  // Get first role if there are multiple

            if(!empty($user) && $userRole != 'Super Admin'){

                $franchise = Franchise::where('user_id',$user->id)->first();

            }

            // print_r($userRole); exit;

          

            // Constants for status values

            $statusPending = "0";

            $statusComplete = "1";

        

            $quotationQuery = Quotation::query();



            // If the user is a "Franchise", filter the quotations by franchise_id

            if ($userRole === 'Vendor') {

                $quotationQuery->where('franchise_id', $franchise->id);

            }



            // Get the list of all quotations (without filtering by status)

            $quotationList = $quotationQuery->get();

            

            // Clone the query builder for counting quotations by status

            $quotationQueryForCount = clone $quotationQuery;



            // Count the number of pending quotations

            $quotationListPending = $quotationQueryForCount

                ->where('status', $statusPending)

                ->count();



            // Clone the query builder again for fetching completed quotations

            $quotationQueryForComplete = clone $quotationQuery;



            // Retrieve the list of completed quotations

            $quotationListComplete = $quotationQueryForComplete

                ->where('status', $statusComplete)

                ->count();

            



                // dd([$quotationList, $quotationListPending, $quotationListComplete]);

            // Return view with compacted data   

            return view('admin.quotation.index', compact('quotationList', 'quotationListPending', 'quotationListComplete'));

        }



    public function create($appointment_id)

    {   

        $data['appointment_id'] = $appointment_id;

        $data['appointment_data'] = Appointment::where('id',$appointment_id)->first();

        $data['product_type'] = Product::distinct('design_sku')->with('ProductType')->get();

         //echo"<pre>";print_r($data['product_type']);die;

        return view("admin.quotation.create", $data);

    }



    public function store(Request $request)

    {

        // echo '<pre>'; print_r($request->all()); exit;

       

        // dd($request->all());

        $request->validate([

            'name' => 'string|max:255',

            'email' => 'email',

            'number' => 'nullable|string|max:255',

            'address' => 'nullable|string',

            'quot_for' => 'nullable|string',

            'cartage' => 'nullable|string',

            // 'section_name' => 'nullable|string',

            // 'item_height' => 'required|array',

            // 'item_width' => 'required|array',

            // 'item_qty' => 'required|array',

            // 'item_unit' => 'required|array',

            // 'item_price' => 'required|array',

            // 'item_discount' => 'required|array',

        ]);



        $lastAppointmentId = Quotation::max("unique_id");

        $nextAppointmentId = $this->generateNextCode($lastAppointmentId, "CAB");

        $request["unique_id"] = $nextAppointmentId;

    

        // Prepare the quotation data

        

        $arrayForInsert = [

            'unique_id' => $request->unique_id,

            'appointment_id' => $request->appoint_id ?? '',

            'franchise_id' =>$request->franchise_id,

            'name' => $request->name ?? '',

            'email' => $request->email ?? '',

            'number' => $request->number ?? '',

            'address' => $request->address ?? '',

            'quot_for' => $request->quotation_for ?? '',

            'cartage' => $request->cartage ?? '',



            'gst_no' =>  $request->gst_uin ?? '',

            'voucher_no' => $request->voucher_no ?? '',

            'buyer_ref' => $request->buyer_ref ?? '',

            'other_ref' =>  $request->other_ref ?? '',

            'dispatch' => $request->dispatch ?? '',

            'destination' => $request->destination ?? '',

            'city_port' => $request->city_port ?? '',

            'terms_delivery' => $request->terms_deliver ?? '',



            // 'section_name' => $request->section_name ?? '',

            'date' => date('Y-m-d'),  // using Y-m-d format



            'created_at' => now(),

            'updated_at' => now(),

        ];

    

        // Create the quotation and get the quotation ID

        $quotation = Quotation::create($arrayForInsert);

        $newQuotationId = $quotation->id;

    

        // Prepare the item data based on the arrays from the form

        $sections = [];

        $items = [];

        $sectionID = [];



        // Iterate over both section_name and product_item arrays together

        foreach ($request->section_name as $index => $section_name) {

            // Ensure section_name exists

            if (isset($section_name)) {

                // Create section data

                $sections[] = [

                    'quotation_id' => $newQuotationId,  // Associate with the created quotation

                    'section_name' => $section_name,    // Access correct section_name

                    'created_at' => now(),

                    'updated_at' => now(),

                ];



                // Loop through the associated product items for this section

                if (isset($request->product_item[$index])) {

                    // Loop through each item in the current section

                    foreach ($request->product_item[$index] as $j => $product_item) {

                        if (isset($request->item_name[$index][$j])) {

                            // Add item data to the items array

							$product_data=Product::where('id',$product_item)->first();

							//echo "<pre>";print_r($product_data);die; 

                            $items[] = [

                                'quotation_id' => $newQuotationId, // Associate with the created quotation

                                'appointment_id' => $request->appoint_id ?? '',

                                'franchise_id' => $request->franchise_id,

                                'section_order' => $section_name, // Set section ID as the quotation ID temporarily (we'll fix it later)

                                'item_order' => $j,  // Item order (starting from 1)

                                'name' => $request->item_name[$index][$j] ?? '', // Access item name correctly

                                'item' => $product_item ?? '',

                                // 'height' => $request->item_height[$index][$j] ?? '',

                                // 'width' => $request->item_width[$index][$j] ?? '',

                                'qty' => $request->item_qty[$index][$j] ?? '',

                                'unit' => $request->item_unit[$index][$j] ?? '',

                                'price' => $request->item_price[$index][$j] ?? '',

                                'discount' => $request->item_discount[$index][$j] ?? '',

								'gst_percentage' =>!empty($product_data->gst_percentage)?$product_data->gst_percentage:NULL,

                                'total_amount' => $request->item_mrp[$index][$j] ?? '',

                                'created_at' => now(),

                                'updated_at' => now(),

                            ];

                        }

                    }

                }

            }

        }



        // Insert sections first

        $insertedSections = QuotationSection::insert($sections);



        // Now retrieve the section IDs from the inserted sections

        if ($insertedSections) {

            // Retrieve section IDs after inserting

            $sectionID = QuotationSection::where('quotation_id', $newQuotationId)->get(['id', 'section_name']);

            

            // Map the section names to their corresponding IDs

            $sectionIDMap = $sectionID->pluck('id', 'section_name')->toArray();



            // dd($sectionIDMap);

            // Update the section_order for each item based on the correct section ID

            foreach ($items as &$item) {

                $item['section_order'] = $sectionIDMap[$item['section_order']] ?? null; // Set section_order based on section name

            }

        }



        // Insert items in bulk

        if (!empty($items)) {

            QuotationItem::insert($items);  

        }



        // Insert all items into the database at once

        



        $appointment = Appointment::findOrFail($request->appoint_id);

        $appointment->status = "3"; // Set the new status value

        $appointment->save(); // Save the changes



        $franchise_data = Franchise::find($request->franchise_id);

        $appointData = Appointment::find($request->appoint_id);



         // send whatsaap Message

        //  $parameters = [

        //     [

        //         'type' => 'text',

        //         'text' => $request->name

        //     ]  

        // ];



         $this->whatsAppService->sendMessageWp('91'.$franchise_data->mobile, 'quotation');

         $this->whatsAppService->sendMessageWp('91'.$appointData->mobile, 'quotation');

        // end send whatsaap Message





        Mail::to($appointment->email)->send(

            new QuotationGeneratedMail($appointment,$newQuotationId)

        );

        return redirect()

            ->route("quotations.list")

            ->with("success", "Quotation created successfully!");

    }



    public function getQuotationsData(Request $request)

    {

        // Retrieve the 'status' input from the request, default to 'pending' if not set

        $status = $request->input('status', 'pending');



        // Use a switch-case for better readability and easier status mapping

        switch ($status) {

            case 'pending':

                $status = '0';

                break;

            case 'complete':

                $status = '1';

                break;

        }





        // Fetch appointments based on the mapped status

        // $quotations = Quotation::with('franchise')->where('status','=', $status)->get();



        $user = Auth::user();

        if(!empty($user)){

            $franchise = Franchise::where('user_id',$user->id)->first();

        }

        $userRole = $user->getRoleNames()->first();  // Get first role if there are multiple

    

        // // Constants for status values

        // $statusPending = "0";

        // $statusComplete = "1";

    

        // Initialize default query builder for all quotations

        $quotationQuery = Quotation::query();

    

        // If the user is a "Franchise", filter the quotations by franchise_id

        if ($userRole == 'Vendor') {

            $quotationQuery->where('franchise_id', $franchise->id);

        }

        $quotationList = $quotationQuery->with('franchise')->where('status','=', (string) $status)->get();

        // Return the data as JSON response

        return response()->json(['data' => $quotationList]);

    }



    public function getAppointmentDetails($id, $type)

    {

        // Fetch the appointment by ID

        $quotation = Quotation::with('quotaitonItem')->findOrFail($id);

        $appointment = Appointment::find($quotation->appointment_id);

        // Use a switch-case for better readability and easier status mapping

        switch ($type) {

            case 'pending':

                $status = 0;

                break;

            case 'complete':

                $status = 1;

                break;

            default:

                $status = 0;  // Default status

                break;

        }

        

        // Check if the appointment's status matches the type passed

        if ($quotation && $quotation->status == $status) {

            return response()->json([

                'status' => 'success',

                'data' => [

                    'quotation' => $quotation,

                    'appointment' => $appointment,

                ],

                'message' => 'Quotations details fetched successfully.'

            ]);

        } else {

            return response()->json([

                'status' => 'error',

                'message' => 'Quotations not found or status mismatch.'

            ]);

        }

    }



    public function deleteQuotationsData($id){

        $appointData = Quotation::findOrFail($id);

    

        // Delete the record

        $appointData->delete();

       



        return redirect()->back()->with('success', 'Quotation deleted successfully.');

    }



    public function downloadQuotationView($quotation_Id){

        $order_data = Quotation::with('appointment', 'franchise', 'quotaitonItem','quotaiton_section')->find($quotation_Id);

        //echo "<pre>";print_r($order_data);die; 



        // $this->whatsAppService->sendMessageWp('91'.$order_data['appointment']['mobile'], 'purchaseorder');

        // $this->whatsAppService->sendMessageWp('91'.$order_data['franchise']['mobile'], 'purchaseorder');

        

        return view('admin.quotation.download_quote', compact('order_data'));

    }



    public function getQuotationData($appointment_id){

        if(!empty($appointment_id)){

            $appointment = Appointment::findorfail($appointment_id);

            if(!empty($appointment)){

                $quotation = Quotation::where([

                    'franchise_id' => $appointment->franchise_id,

                    'appointment_id' => $appointment->id

                ])->first();

                //echo "<pre>";print_r($quotation);die;

                if ($quotation) {

                    $quotation_data = QuotationItem::select(

                        'quotation_id','discount','qty','price','gst_percentage'

                    )->where([

                        'franchise_id' => $quotation->franchise_id,

                        'appointment_id' => $quotation->appointment_id,

                        'quotation_id' => $quotation->id

                    ])->get();

					$total=0;

                   if($quotation_data){

					   foreach($quotation_data as $datas){

						  if($datas['price'] != '' && $datas['gst_percentage'] != ''){

						  $total=$total+(($datas['price']*$datas['qty'])+ ($datas['price']*$datas['qty']*$datas['gst_percentage']/100)-($datas['price']*$datas['qty']*$datas['discount']/100)); 

                          }						  

					   }

				   } 

                    //echo $total;die; 	  			   

                    $quotation_items['quotation_id'] = $datas['quotation_id']; 

                    $quotation_items['total_order_value'] = !empty($total)?$total:0;					

                    $quotation_items['franchise_id'] = $appointment->franchise_id;

                    $quotation_items['appointment_id'] = $appointment->id;



                    return $quotation_items;

                } else {

                    // Handle case where no quotation is found, if necessary

                    $quotation_items = collect(); // return an empty collection or handle as needed

                }

            }else{

                return redirect()->back()->with('error', 'Appointment not found.');

            }

        }else{

            return redirect()->back()->with('error', 'Appointment blank.');

        }

    }



    public function whatsapptest(){

       

        $parameters = [

            [

                'type' => 'text',

                'text' => 'R1'

            ],

            [

                'type' => 'text',

                'text' => 'R2'

            ],

            [

                'type' => 'text',

                'text' => 'R3'

            ],

            [

                'type' => 'text',

                'text' => 'R4'

            ],

            [

                'type' => 'text',

                'text' => 'R5'

            ]

        ];



        try {

            // Call the sendMessage function

            $response = $this->whatsAppService->sendMessage('919718392908', 'installationscheduled', $parameters);



            return response()->json([

                'success' => true,

                'response' => json_decode($response)

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'error' => $e->getMessage()

            ], 500);

        }

        

    }

}

