<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierCollection;
use App\Models\SupplierCollectionDesign;
use App\Models\Color;
use App\Models\Composition;
use App\Models\DesignType;
use App\Models\ProductType;
use App\Models\Type;
use App\Models\Usage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function create()
    {
        $suppliers = Supplier::all();
        $supplierCollection = SupplierCollection::all();
        $supplierCollectionDesign = SupplierCollectionDesign::all();
        $colours = Color::all();
        $compositions = Composition::all();
        $designTypes = DesignType::all();
        $productTypes = ProductType::all();
        $types = Type::all();
        $usages = Usage::all();

        return view(
            "admin.product.create",
            compact(
                "suppliers",
                "supplierCollection",
                "supplierCollectionDesign",
                "colours",
                "compositions",
                "designTypes",
                "productTypes",
                "types",
                "usages"
            )
        );
    }

    // Store the product in the database

    public function store(Request $request)
    {
        $lastTallyCode = Product::max("tally_code");
        $nextTallyCode = $this->generateNextCode($lastTallyCode, "CAB");

        $request->validate([
            "product_name" => "required|string|max:255",
            "file_number" => "required|string|unique:products,file_number",
            "supplier_name" => "required|integer",
            "rubs_martendale" => "nullable|string|max:255",
            "width" => "nullable|string|max:255",
            "image" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "image_alt" => "required|string|max:255",
            "colour" => "required|string|max:255",
            "composition" => "required|array|min:1",
            "composition.*" => "string|max:255",
            "design_type" => "required|array|min:1",
            "design_type.*" => "string|max:255",
            "usage" => "required|array|min:1",
            "usage.*" => "string|max:255",
            "type" => "required|array|min:1",
            "type.*" => "string|max:255",
            "note" => "nullable|string|max:255",
            "supplier_price" => "required|numeric|min:0",
            "freight" => "required|numeric|min:0",
            "profit_percentage" => "required|numeric|min:0",
            "gst_percentage" => "required|numeric|min:0",
            "mrp" => "required|numeric|min:0",
        ]);

        $imagePath = null;
        if ($request->hasFile("image")) {
            $imagePath = $request->file("image")->store("products", "public");
        }

        $productData = [
            "product_name" => $request->input("product_name"),
            "tally_code" => $nextTallyCode,
            "file_number" => $request->input("file_number"),
            "supplier_name" => $request->input("supplier_name"),
            "supplier_collection" => $request->input("supplier_collection"),
            "supplier_collection_design" => $request->input(
                "supplier_collection_design"
            ),
            "design_sku" => $request->input("design_sku"),
            "rubs_martendale" => $request->input("rubs_martendale"),
            "width" => $request->input("width"),
            "image" => $imagePath,
            "image_alt" => $request->input("image_alt"),
            "colour" => $request->input("colour"),
            "composition" => json_encode($request->input("composition")),
            "design_type" => json_encode($request->input("design_type")),
            "usage" => json_encode($request->input("usage")),
            "type" => json_encode($request->input("type")),
            "note" => $request->input("note"),
            "currency" => $request->input("currency"),
            "supplier_price" => $request->input("supplier_price"),
            "freight" => $request->input("freight"),
            "duty_percentage" => $request->input("duty_percentage"),
            "profit_percentage" => $request->input("profit_percentage"),
            "gst_percentage" => $request->input("gst_percentage"),
            "mrp" => $request->input("mrp"),
            "unit" => $request->input("unit"),
        ];

        Product::create($productData);

        return redirect()
            ->route("products.index")
            ->with("success", "Product created successfully!");
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
        $products = Product::with(
            "ProductType",
            "Supplier",
            "SupplierCollection",
            "SupplierCollectionDesign"
        )->orderBy('created_at', 'desc');

        $filters = [
            "type" => "type",
            "supplier_name" => "supplier_name",
            "supplier_collection" => "supplier_collection",
            "supplier_collection_design" => "supplier_collection_design",
            "colour" => "colour",
            "composition" => "composition",
            "usage" => "usage",
            "design_type" => "design_type",
        ];

        // Loop through the filters and apply the conditions

        foreach ($filters as $key => $column) {
            $value = request()->get($key);
            if ($value && $value !== "Select") {
                if ($column == 'type' || $column == 'composition' || $column == 'usage' || $column == 'design_type') {
                    $products->whereJsonContains($column, $value);
                } else {
                    if (is_array($value)) {
                        $products->whereIn($column, $value);
                    } else {
                        $products->where($column, $value);
                    }
                }
            }
        }

        $products = $products->paginate(5000);
        $totalProducts = $products->total();
        $usages = Usage::all();
        $colours = Color::all();
        $compositions = Composition::all();
        $designTypes = DesignType::all();
        $types = Type::all();
        $suppliers = Supplier::all();
        $supplierCollection = SupplierCollection::all();
        $supplierCollectionDesign = SupplierCollectionDesign::all();
        $productTypes = ProductType::all();

        return view("admin.product.index", 
            compact(
                "totalProducts",
                "products",
                "usages",
                "colours",
                "compositions",
                "designTypes",
                "types",
                "suppliers",
                "supplierCollection",
                "supplierCollectionDesign",
                "productTypes"
            )
        );
    }

    function data_log()
    {
        $post = $_POST;
        $status = $post["status"] ?? "";
        $columns = [
            "retailer_name",
            "uniquecode",
            "gst",
            "pan",
            "email",
            "mobile",
            "image",
            "status",
            "created_at",
        ];
        $limit = $post["length"] ?? 10;
        $start = $post["start"] ?? 0;
        $orderColumnIndex = $post["order"][0]["column"] ?? 0;
        $order = $columns[$orderColumnIndex] ?? "created_at";
        $dir = $post["order"][0]["dir"] ?? "desc";
        $distri_code = session_uniquecode(session("uniquecode"));
        $mapp_q = \App\Model\RetailerRmMapping::select("retailer_code")->where(
            "distributor_code",
            $distri_code
        );

        $retailer_array = $mapp_q->get()->pluck("retailer_code");
        $retailers_query = User::select(
            "id",
            "retailer_name",
            "uniquecode",
            "gst",
            "pan",
            "email",
            "mobile",
            "image",
            "status",
            "created_at"
        )->whereIn("uniquecode", $retailer_array);
        if (!empty($_POST["status"])) {
            if ($_POST["status"] == "Disapproved") {
                $retailers_query->where("status", "like", "%" . $status . "%");
            }
        }

        if (!empty($_POST["filter_status"])) {
            $retailers_query->where(
                "status",
                "like",
                "%" . $_POST["filter_status"] . "%"
            );
        }

        if (!empty(request("start_date"))) {
            $retailers_query->where(
                "created_at",
                ">=",
                date("Y-m-d", strtotime(request("start_date")))
            );
        }

        if (!empty(request("end_date"))) {
            $retailers_query->where(
                "updated_at",
                "<=",
                date("Y-m-d", strtotime(request("end_date")))
            );
        }

        $retailers_query->with([
            "credit_data" => function ($q) use ($distri_code) {
                $q->where("distributor_code", $distri_code);
            },
        ]);

        // Apply search conditions

        if (!empty($post["search"]["value"])) {
            $searchValue = $post["search"]["value"];

            $retailers_query->where(function ($query) use ($searchValue) {
                $query
                    ->where("retailer_name", "LIKE", "%$searchValue%")
                    ->orWhere("mobile", "LIKE", "%$searchValue%")
                    ->orWhere("email", "LIKE", "%$searchValue%")
                    ->orWhere("gst", "LIKE", "%$searchValue%")
                    ->orWhere("pan", "LIKE", "%$searchValue%");
            });
        }

        $totalRecords = $retailers_query->count();
        $retailers_query->orderBy($order, $dir);
        $retailers = $retailers_query
            ->offset($start)
            ->limit($limit)
            ->get();
        $data_array = [];

        foreach ($retailers as $key => $retailer) {
            $t = strtotime($retailer["created_at"]);

            $retailer["time"] = date("d M Y", $t);

            $data_array[] = [
                $key + 1,

                $retailer["image"] !=
                    "https://redi-bucket.s3.ap-south-1.amazonaws.com/redi/users/image/Distri_109_2024-08-19%2010%3A00%3A26.png" &&
                    !empty($retailer["image"])
                    ? '<a href="' .
                    $retailer["image"] .
                    '" class="fancybox"><img src="' .
                    $retailer["image"] .
                    '" height="30px"></a> ' .
                    ucwords($retailer["retailer_name"]) .
                    " ( " .
                    $retailer["uniquecode"] .
                    " )"
                    : '<img src="" >' .
                    ucwords($retailer["retailer_name"]) .
                    " ( " .
                    $retailer["uniquecode"] .
                    " )",

                $retailer["gst"],

                $retailer["pan"],

                $retailer["email"],

                $retailer["mobile"],

                isset($retailer["credit_data"]["credit_limit"])
                    ? $retailer["credit_data"]["credit_limit"]
                    : 0,

                '<div class="toggle-btn ' .
                    ($retailer["status"] == "Approved" ? "active" : "") .
                    '">

                    <input type="checkbox" ' .
                    ($retailer["status"] == "Approved" ? "checked" : "") .
                    ' class="cb-value" data-id="' .
                    custom_encode($retailer["id"]) .
                    '" />

                    <span class="round-btn"></span></div>',

                $retailer["time"],

                '<div class="assign_edit"><a href="rm/change_mapping/' .
                    custom_encode($retailer["id"]) .
                    '" class="fancybox ajax"><button class="redi_btn xs"><i class="fa fa-edit"></i>Assign</button></a> 

                <a href="' .
                    "retailer/edit/" .
                    custom_encode($retailer["id"]) .
                    '" class=""><button class="redi_btn xs"><i class="fa fa-edit"></i> Edit</button></a></div>',

                '<a href="field-force/rm/edit_rm_history/' .
                    custom_encode($retailer["id"]) .
                    '" class="fancybox ajax"><button class="btn btn-info btn-sm"><i class="fa fa-history"></i></button></a>',
            ];
        }

        // Prepare JSON response data

        $json_data = [
            "draw" => intval($post["draw"]),

            "recordsTotal" => $totalRecords,

            "recordsFiltered" => $totalRecords,

            "data" => $data_array,
        ];

        // Return JSON response

        return response()->json($json_data);
    }

    // Show the form for editing the product

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $product->usage = json_decode($product->usage, true);
        $product->composition = json_decode($product->composition, true);
        $product->design_type = json_decode($product->design_type, true);
        $product->type = json_decode($product->type, true);
        $suppliers = Supplier::all();
        $supplierCollections = SupplierCollection::all();
        $supplierCollectionDesigns = SupplierCollectionDesign::all();
        $colours = Color::all();
        $compositions = Composition::all();
        $designTypes = DesignType::all();
        $productTypes = ProductType::all();
        $types = Type::all();
        $usages = Usage::all();

        return view(
            "admin.product.edit",
            compact(
                "product",
                "suppliers",
                "supplierCollections",
                "supplierCollectionDesigns",
                "colours",
                "compositions",
                "designTypes",
                "productTypes",
                "types",
                "usages"
            )
        );
    }

    // Update the product in the database

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $imagePath = $product->image;

        // if ($request->hasFile("image") || $request->hasFile("image") == 'true') {
        //     $imagePath = $request->file("image")->store("products", "public");
        // }

        // dd($request->all(), $request->file('image'));
        if ($request->hasFile("image") || !empty($request->file('image'))) {
            // Optionally delete the old image if it exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
        
            // Store the new image and get the path
            $imagePath = $request->file("image")->store("products", "public");
        }

        

        // Prepare the data for insertion

        $productData = [
            "product_name" => $request->input("product_name"),
            "tally_code" => $request->input("tally_code"),
            "file_number" => $request->input("file_number"),
            "supplier_name" => $request->input("supplier_name"),
            "supplier_collection" => $request->input("supplier_collection"),
            "supplier_collection_design" => $request->input(
                "supplier_collection_design"
            ),
            "design_sku" => $request->input("design_sku"),
            "rubs_martendale" => $request->input("rubs_martendale"),
            "width" => $request->input("width"),
            "image" => $imagePath,
            "image_alt" => $request->input("image_alt"),
            "colour" => $request->input("colour"),
            "composition" => json_encode($request->input("composition")),
            "design_type" => json_encode($request->input("design_type")),
            "usage" => json_encode($request->input("usage")),
            "type" => json_encode($request->input("type")),
            "note" => $request->input("note"),
            "currency" => $request->input("currency"),
            "supplier_price" => $request->input("supplier_price"),
            "freight" => $request->input("freight"),
            "duty_percentage" => $request->input("duty_percentage"),
            "profit_percentage" => $request->input("profit_percentage"),
            "gst_percentage" => $request->input("gst_percentage"),
            "mrp" => $request->input("mrp"),
            "unit" => $request->input("unit"),
        ];

        $product->update($productData);

        return redirect()
            ->route("products.index")
            ->with("success", "Product updated successfully");
    }

    // Remove the product from the database

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()
            ->route("products.index")
            ->with("success", "Product deleted successfully");
    }

    public function show($id)
    {
        $product = Product::findOrFail($id); // Retrieve the product by ID

        return view("admin.product.view", compact("product")); // Pass the product data to the view
    }

    public function getProductDetails(Request $request, $id)
    {
        //dd('raj');
        $product = Product::with([
            "productType",
            "supplier",
            "supplierCollection",
            "supplierCollectionDesign",
        ])->find($id);

        if ($product) {
            return response()->json(["product" => $product]);
        } else {
            return response()->json(["message" => "Product not found"], 404);
        }
    }

    public function download_csv()
    {
        // Fetch the required data from the database

        $query = Product::with(
            "ProductType",
            "Supplier",
            "SupplierCollection",
            "SupplierCollectionDesign"
        )->orderBy('id', 'desc');

        $products = $query->get();

        // Prepare the CSV data

        $header = [
            "Date",
            "Product Name",
            "Type",
            "Tally Code",
            "File Number",
            "Supplier Name",
            "Supplier Collection",
            "Supplier Collection Design",
            "Design SKU",
            "Rubs Martendale",
            "Width",
            "Composition",
            "Design_type",
            "Usage",
            "Note",
            "Currency",
            "Supplier Price",
            "Freight",
            "Duty Percentage",
            "Profit Percentage",
            "GST Percentage",
            "MRP",
            "Unit",
        ];

        // Create an array for CSV rows, starting with the header

        $data[] = $header;

        // Populate CSV rows with product data

        if (!empty($products)) {
            foreach ($products as $product) {
                $composition = json_decode($product->composition, true);
                $composition = json_last_error() === JSON_ERROR_NONE ? $composition : "Invalid JSON";

                $design_type = json_decode($product->design_type, true);
                $design_type = json_last_error() === JSON_ERROR_NONE ? $design_type : "Invalid JSON";

                $usage = json_decode($product->usage, true);
                $usage = json_last_error() === JSON_ERROR_NONE ? $usage : "Invalid JSON";

                // Convert arrays to strings if needed for CSV
                $composition_str = is_array($composition) ? implode(", ", $composition) : $composition;
                $design_type_str = is_array($design_type) ? implode(", ", $design_type) : $design_type;
                $usage_str = is_array($usage) ? implode(", ", $usage) : $usage;
                $row = [
                    date("d M Y", strtotime($product->created_at)), // Format the date
                    $product->product_name,
                    optional($product->ProductType)->name, // Get related data safely using optional
                    $product->tally_code,
                    $product->file_number,
                    optional($product->Supplier)->name, // Get related data safely using optional
                    optional($product->SupplierCollection)->collection_name, // Get related data safely using optional
                    optional($product->SupplierCollectionDesign)->design_name, // Get related data safely using optional
                    $product->design_sku,
                    $product->rubs_martendale,
                    $product->width,
                    $composition_str,
                    $design_type_str,
                    $usage_str,
                    $product->note,
                    $product->currency,
                    $product->supplier_price,
                    $product->freight,
                    $product->duty_percentage,
                    $product->profit_percentage,
                    $product->gst_percentage,
                    $product->mrp,
                    $product->unit,
                ];
                //dd($row);
                $data[] = $row;
            }
        } else {
            $data[] = [];
        }

        // Call helper function to generate and download the CSV file

        return $this->array_to_csv($data, "Products.csv");
    }

    public function array_to_csv(
        $array,

        $download = ""
    ) {
        if ($download != "") {
            // Set the headers for CSV file download

            header("Content-Type: text/csv");

            header(
                'Content-Disposition: attachment; filename="' . $download . '"'
            );
        }

        // Open output stream to write the CSV data

        ob_start();

        $f = fopen("php://output", "w");

        if ($f === false) {
            // Handle the error if fopen fails

            return response()->json(
                ["error" => "Unable to open output stream for CSV"],
                500
            );
        }

        // Write each line of the array into the CSV file

        foreach ($array as $line) {
            if (fputcsv($f, $line) === false) {
                fclose($f);

                // Handle the error if fputcsv fails

                return response()->json(
                    ["error" => "Error writing CSV data"],
                    500
                );
            }
        }

        // Close the file and get the output content

        fclose($f);
        $csvData = ob_get_contents();
        ob_end_clean();

        // Output or return the CSV data

        if ($download == "") {
            return $csvData; // For internal usage or tests
        } else {
            echo $csvData; // For download
        }
    }
}
