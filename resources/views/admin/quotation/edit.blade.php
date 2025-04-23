@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="dataOverviewSection mt-3 mb-3">
    <form action="{{ route('products.update', $product->id ?? '') }}" method="POST" enctype="multipart/form-data" class="mt-3" id="productForm">
        @csrf
        @if(isset($product))
        @method('PUT') <!-- To use the PUT method for updating -->
        @endif
        <div class="dataOverview mt-3">
            <h6 class="m-0">{{ isset($product) ? 'Edit Product' : 'Add New Product' }}</h6>
            <hr class="m-0 mt-2 mb-2">
            <div class="row mb-2">
                <div class="col-md-4">
                    <label class="form-label m-0 mb-1" for="type">Type <span class="text-danger">*</span></label>
                    <select name="product_name" id="product_name" class="form-select w-100 select2" required>
                        <option value="opt1">Select</option>
                        @foreach ($productTypes as $productType)
                        <option value="{{ $productType->id }}"
                            {{ ($product->product_name == $productType->id) ? 'selected' : '' }}>
                            <!-- {{ isset($product) && is_string($product->product_name) && in_array($productType->product_type, explode(',', $product->product_name)) ? 'selected' : '' }}> -->
                            {{ $productType->product_type }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="mb-1 w-100">
                        <label for="code" class="form-label mb-1">Code</label>
                        <input type="text" class="form-control w-100" id="tally_code" name="tally_code" value="{{ isset($product) ? $product->tally_code : '' }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-1 w-100">
                        <label for="file_number" class="form-label mb-1">File Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control w-100" id="file_number" name="file_number" value="{{ old('file_number', isset($product) ? $product->file_number : '') }}" required>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4">
                    <label for="supplier_name" class="form-label m-0 mb-1">Supplier Name <span class="text-danger">*</span></label>
                    <select name="supplier_name" id="supplier_name" class="form-select w-100 select2" required>
                        <option value="">Select</option>
                        @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ isset($product) && $product->supplier_name == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="supplier_collection" class="form-label m-0 mb-1">Supplier Collection <span class="text-danger">*</span></label>
                    <select name="supplier_collection" id="supplier_collection" class="form-select w-100 select2" required>
                        <option value="">Select</option>
                        @foreach ($supplierCollections as $supplierCollection)
                        <option value="{{ $supplierCollection->id }}" {{ isset($product) && $product->supplier_collection == $supplierCollection->id ? 'selected' : '' }}>{{ $supplierCollection->collection_name }}</option>
                        @endforeach
                        <!-- Populate based on selected supplier -->
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="supplier_collection_design" class="form-label m-0 mb-1">Supplier Collection Design <span class="text-danger">*</span></label>
                    <select name="supplier_collection_design" id="supplier_collection_design" class="form-select w-100 select2" required>
                        <option value="">Select</option>
                        @foreach ($supplierCollectionDesigns as $supplierCollectionDesign)
                        <option value="{{ $supplierCollectionDesign->id }}" {{ isset($product) && $product->supplier_collection_design == $supplierCollectionDesign->id ? 'selected' : '' }}>{{ $supplierCollectionDesign->design_name }}</option>
                        @endforeach
                        <!-- Populate based on selected collection -->
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4">
                    <div class="mb-1 w-100">
                        <label for="design_sku" class="form-label mb-1">Design SKU</label>
                        <input type="text" class="form-control w-100" id="design_sku" name="design_sku" value="{{ isset($product) ? $product->design_sku : '' }}" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-1 w-100">
                        <label for="rubs_martendale" class="form-label mb-1">Rubs/Martendale</label>
                        <input type="text" class="form-control w-100" id="rubs_martendale" name="rubs_martendale" value="{{ old('rubs_martendale', isset($product) ? $product->rubs_martendale : '') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-1 w-100">
                        <label for="width" class="form-label mb-1">Width</label>
                        <input type="text" class="form-control w-100" id="width" name="width" value="{{ old('width', isset($product) ? $product->width : '') }}">
                    </div>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="mb-2 w-100 d-flex justify-content-start">
                <img src="{{ isset($product) && $product->image ? asset('storage/' . $product->image) : asset('admin/images/image.jpg') }}" class="rounded" id="imagePreview" alt="" width="140" height="140">
                <div class="w-100 ms-3">
                    <div>
                        <label for="image" class="form-label mb-1">Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control w-100" id="image" name="image" value="{{ isset($product) && $product->image ? asset('storage/' . $product->image) : '' }}" onchange="previewImage(event)">
                    </div>
                    <div>
                        <label for="image_alt" class="form-label mb-1">Image Alt <span class="text-danger">*</span></label>
                        <input type="text" class="form-control w-100" id="image_alt" name="image_alt" value="{{ old('image_alt', isset($product) ? $product->image_alt : '') }}" required>
                    </div>
                </div>
            </div>

            <!-- Other Selects for Multiple Values -->
            <div class="row mb-2">
                <div class="col-md-4">
                    <label for="usage" class="form-label m-0 mb-1">Usage <span class="text-danger">*</span></label>
                    <select name="usage[]" id="usage" class="mySelect for" multiple="multiple" style="width: 100%">
                        @foreach ($usages as $usage)
                        <option value="{{ $usage->usages }}"
                            @if(is_array($product->usage))
                            {{ in_array($usage->usages, $product->usage) ? 'selected' : '' }}
                            @else
                            {{ $usage->usages == $product->usage ? 'selected' : '' }}
                            @endif>
                            {{ $usage->usages }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="type" class="form-label m-0 mb-1">Type (Technical specs) <span class="text-danger">*</span></label>
                    <select name="type[]" id="type" class="mySelect for" multiple="multiple" style="width: 100%">
                        @foreach ($types as $type)
                        <option value="{{ $type->type }}"
                            @if(is_array($product->type))
                            {{ in_array($type->type, $product->type) ? 'selected' : '' }}
                            @else
                            {{ $type->type == $product->type ? 'selected' : '' }}
                            @endif>
                            {{ $usage->usages }}
                        </option>
                        @endforeach

                    </select>
                </div>
                <div class="col-md-4">
                    <label for="design_type" class="form-label m-0 mb-1">Design Type <span class="text-danger">*</span></label>
                    <select name="design_type[]" id="design_type" class="mySelect for" multiple="multiple" style="width: 100%">
                        @foreach ($designTypes as $designType)
                        <option value="{{ $designType->design_type }}"
                            {{ isset($product) && is_array($product->design_type) && in_array($designType->design_type, $product->design_type) ? 'selected' : '' }}>
                            {{ $designType->design_type }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Additional fields for other attributes -->
            <div class="row mb-2">
                <div class="col-md-4">
                    <label for="colour" class="form-label m-0 mb-1">Colour <span class="text-danger">*</span></label>
                    <select name="colour[]" id="colour" class="mySelect for" multiple="multiple" style="width: 100%">
                        @foreach ($colours as $colour)
                        <option value="{{ $colour->color }}"
                            {{ isset($product) && is_array($product->colour) && in_array($colour->color, $product->colour) ? 'selected' : '' }}>
                            {{ $colour->color }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="composition" class="form-label m-0 mb-1">Composition<span class="text-danger">*</span></label>
                    <select name="composition[]" id="composition" class="mySelect for" multiple="multiple" style="width: 100%">
                        @foreach ($compositions as $composition)
                        <option value="{{ $composition->composition }}"
                            {{ isset($product) && is_array($product->composition) && in_array($composition->composition, $product->composition) ? 'selected' : '' }}>
                            {{ $composition->composition }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-2">
            <div class="col-md-12">
                <label for="noteInput" class="form-label mb-1">Note</label>
                <textarea name="note" id="noteInput" class="form-control w-100">{{ old('note', isset($product) ? $product->note : '') }}</textarea>
            </div>
        </div>
        <hr class="m-0 mt-4 mb-2">
        <div class="row mb-2">
            <div class="col-md-3">
                <div class="mb-1 w-100">
                    <label for="CostPriceInput" class="form-label mb-1">Cost Price <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" id="supplierPriceInput" name="supplier_price" value="{{ old('supplier_price', isset($product) ? $product->supplier_price : '') }}">
                </div>
            </div>

            <div class="col-md-3">
                <div class="mb-1 w-100">
                    <label for="freightInput" class="form-label mb-1">Freight <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" id="freightInput" name="freight" value="{{ old('freight', isset($product) ? $product->freight : '') }}">
                </div>
            </div>

            <div class="col-md-3">
                <div class="mb-1 w-100">
                    <label for="profitInput" class="form-label mb-1">Profit % <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" id="profitInput" name="profit_percentage" value="{{ old('profit_percentage', isset($product) ? $product->profit_percentage : '') }}">
                </div>
            </div>

            <div class="col-md-3">
                <div class="mb-1 w-100">
                    <label for="gstInput" class="form-label mb-1">GST % <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" id="gstInput" name="gst_percentage" value="{{ old('gst_percentage', isset($product) ? $product->gst_percentage : '') }}">
                </div>
            </div>

            <div class="col-md-3">
                <div class="mb-1 w-100">
                    <label for="mrpInput" class="form-label mb-1">MRP <span class="text-danger">*</span></label>
                    <input type="number" class="form-control w-100" id="mrpInput" name="mrp" value="{{ old('mrp', isset($product) ? $product->mrp : 0) }}" readonly>
                </div>
            </div>
        </div>
        </div>
        

        <div class="mt-3 d-flex gap-3 mb-4">
            <button type="submit" class="btn primary-btn">Update Product</button>
            <button type="reset" class="btn secondary-btn">Cancel</button>
        </div>
</div>
</form>
</div>

<script>
    // Image Preview
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('imagePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // jQuery Form Validation
        $(document).ready(function() {
            $('#productForm').validate({
                rules: {
                    type: {
                        required: true
                    },
                    file_number: {
                        required: true
                    },
                    supplier_name: {
                        required: true
                    },
                    supplier_collection: {
                        required: true
                    },
                    supplier_collection_design: {
                        required: true
                    },
                    image_alt: {
                        required: true
                    }
                },
                messages: {
                    type: {
                        required: "Please select a type."
                    },
                    file_number: {
                        required: "Please enter the file number."
                    },
                    supplier_name: {
                        required: "Please select a supplier."
                    },
                    supplier_collection: {
                        required: "Please select a supplier collection."
                    },
                    supplier_collection_design: {
                        required: "Please select a supplier collection design."
                    },
                    image_alt: {
                        required: "Please enter an image alt text."
                    }
                },
                errorElement: "div",
                errorPlacement: function(error, element) {
                    error.addClass("form-text text-danger xsmall");
                    error.insertAfter(element);
                },
                highlight: function(element) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function(element) {
                    $(element).removeClass("is-invalid").addClass("is-valid");
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });

        // Supplier Name Change Handler
        $('#supplier_name').change(function() {
            const supplierId = $(this).val();

            // Clear dependent dropdowns
            $('#supplier_collection').empty().append('<option value="">Select Supplier Collection</option>');
            $('#supplier_collection_design').empty().append('<option value="">Select Supplier Collection Design</option>');

            if (supplierId) {
                // Fetch Supplier Collections
                $.ajax({
                    url: `/supplier-collection/${supplierId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data); // Check the server response
                        if (data.length === 0) {
                            $('#supplier_collection').append('<option value="" disabled>No collections found</option>');
                        } else {
                            data.forEach(item => {
                                $('#supplier_collection').append(`<option value="${item.id}">${item.collection_name}</option>`);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error occurred: " + error);
                        console.error("Response: " + xhr.responseText);
                        alert('Error retrieving collections');
                    }
                });
            }
        });

        // Supplier Collection Change Handler
        $('#supplier_collection').change(function() {
            const collectionId = $(this).val();
            const supplierId = $('#supplier_name').val();

            // Clear dependent dropdown
            $('#supplier_collection_design').empty().append('<option value="">Select Supplier Collection Design</option>');

            if (collectionId && supplierId) {
                // Fetch Supplier Collection Designs
                $.ajax({
                    url: `/supplier-collection-designs/${supplierId}/${collectionId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data); // Check the server response
                        if (data.length === 0) {
                            $('#supplier_collection_design').append('<option value="" disabled>No designs found</option>');
                        } else {
                            data.forEach(item => {
                                $('#supplier_collection_design').append(`<option value="${item.id}">${item.design_name}</option>`);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error occurred: " + error);
                        console.error("Response: " + xhr.responseText);
                        alert('Error retrieving designs');
                    }
                });
            }
        });
    });

    // Function to calculate MRP dynamically
    function calculateMRP() {
        // Get the input values
        var supplierPrice = parseFloat(document.getElementById("supplierPriceInput").value) || 0;
        var freight = parseFloat(document.getElementById("freightInput").value) || 0;
        var profitPercentage = parseFloat(document.getElementById("profitInput").value) || 0;
        var gstPercentage = parseFloat(document.getElementById("gstInput").value) || 0;

        // Validate inputs
        if (supplierPrice <= 0 || freight < 0 || profitPercentage < 0 || gstPercentage < 0) {
            document.getElementById("mrpInput").value = "";
            return; // Return early if any value is invalid
        }

        // Calculate CPF (Cost Plus Freight)
        var cpf = supplierPrice + freight;
        var profit = cpf * (profitPercentage / 100);
        var mrpBeforeGST = cpf + profit;
        var gstAmount = mrpBeforeGST * (gstPercentage / 100);
        var finalMRP = mrpBeforeGST + gstAmount;
        finalMRP = Math.round(finalMRP);

        // Display MRP
        document.getElementById("mrpInput").value = finalMRP;
    }

    // Attach event listeners to inputs to trigger MRP calculation
    document.getElementById("supplierPriceInput").addEventListener("input", calculateMRP);
    document.getElementById("freightInput").addEventListener("input", calculateMRP);
    document.getElementById("profitInput").addEventListener("input", calculateMRP);
    document.getElementById("gstInput").addEventListener("input", calculateMRP);

    // Initialize the calculation on page load
    window.onload = calculateMRP;
</script>

@endsection