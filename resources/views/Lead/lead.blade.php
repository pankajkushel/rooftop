@extends('admin.layouts.app')

@section('title', 'Lead List')

@section('content')

<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0">All Leads <span class="fw-normal text-muted">({{ $totalLeads }})</span></h6>
        <!-- <a href="{{ route('leadcreate') }}" class="primary-btn addBtn">+ Add
            Lead</a> -->
            <button class="btn text-white shadow-sm" id="createLeadBtn" data-bs-toggle="modal" data-bs-target="#createLeadModal" style="background-color: #052c65">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create Lead
        </button>
    </div>
    <div class="dataOverview mt-3">
        <!-- <div class="d-flex align-items-center justify-content-end mb-3">
            <a class="secondary-btn me-2 addBtn" href="{{ url('products/download/csv') }}"><i class="bi bi-cloud-arrow-down me-2"></i>
                Export Data</a>
            <a class="secondary-btn addBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                aria-controls="offcanvasRight"><i class="bi bi-filter me-2"></i> Filter</a>
        </div> -->
        <div class="table-responsive">
            <table class="table" id="projectsTable">
                <thead>
                    <tr>
                    <th>SR.</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                           
                            <th>Source</th>
                            <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($leads as $lead)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $lead->full_name }}</td>
                            <td>{{ $lead->email }}</td>
                            <td>{{ $lead->phone }}</td>
                            <td>
                                @if($lead->status == 'pending')
                                    <span class="badge badge-pending">Pending</span>
                                @elseif($lead->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @elseif($lead->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-secondary">Unknown</span>
                                @endif
                            </td>
                            
                            <td>{{ $lead->source }}</td>
                            <td>
                            <div class="dropdown">
                                <i class="bi bi-three-dots-vertical" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false"></i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item small"
                                            href="{{ route('leadEdit', $lead->id) }}">Edit</a></li>
                                    <li>
                                        <a class="dropdown-item small viewProductLink" data-bs-toggle="offcanvas"
                                            href="#ProductView" role="button" aria-controls="ProductView"
                                            data-product-id="{{ $lead->id }}">
                                            View
                                        </a>
                                    </li>
                                    <li><a class="dropdown-item small" href="javascript:"
                                            onclick="openDeleteModal('{{ $lead->id }}')">Delete</a></li>
                                            <li>
                                            @if(!$lead->is_converted)
                                                <form action="{{ route('leads.convert', $lead->id) }}" method="POST" class="d-block mb-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm  text-start">
                                                        Add to Customer
                                                    </button>
                                                </form>
                                           
                                            @endif
                                            </li>
                                </ul>
                                
                            </div>
                        </td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>




<!-- delete modal start -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="deleteUserForm" method="POST"
                action="{{ route('products.destroy', ['product' => '__product_id__']) }}" autocomplete="off">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteUserModalLabel">Delete Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this product?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="secondary-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="primary-btn">Delete</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- delete modal end -->

<!-- Create Lead Modal -->
<div class="modal fade" id="createLeadModal" tabindex="-1" aria-labelledby="createLeadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('leadcreate') }}" method="post" id="leadForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="full_name">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="full_name" name="full_name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="source">Source</label>
                                <select class="form-control" id="source" name="source">
                                    <option value="">Select Source</option>
                                    <option value="Website">Website</option>
                                    <option value="Referral">Referral</option>
                                    <option value="Social Media">Social Media</option>
                                    <option value="Advertisement">Advertisement</option>
                                </select>
                            </div>
                       
                            <div class="mb-3">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end border-top pt-3">
                            <button type="reset" class="btn btn-secondary me-2">Reset</button>
                            <button type="submit" class="btn text-white" style="background-color: #052c65">Create Lead</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
<script>
    function clearForm() {
        document.getElementById("filter_form").reset();
    }
    // Function to handle form submission
    document.getElementById("filter_form").addEventListener("submit", function(event) {
        event.preventDefault();  // Prevent default form submission

        // Construct the URL dynamically based on selected filters
        const form = new FormData(this);
        const params = new URLSearchParams();

        form.forEach((value, key) => {
            if (value && value !== 'Select' && value !== '') {
                params.append(key, value);
            }
        });

        // Build the final URL with selected filters
        const url = `{{ url('products') }}?${params.toString()}`;
        
        // Redirect to the new URL (this simulates submitting the form)
        window.location.href = url;
    });

    // Clear form function
    function clearForm() {
        document.getElementById("filter_form").reset();
    }
</script>
<script>
    function openDeleteModal(productId) {
        // Update the form action dynamically
        const form = document.getElementById('deleteUserForm');
        const actionUrl = "{{ route('products.destroy', ['product' => ':productId']) }}".replace(':productId', productId);
        form.action = actionUrl;

        // Show the modal (assuming you're using Bootstrap's modal)
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
        deleteModal.show();
    }
</script>
<script>
    $(document).ready(function() {
        
        $('.viewProductLink').on('click', function() {
            var productId = $(this).data('product-id'); // Get the product ID from the clicked link

            // Send AJAX request to fetch product details
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: '{{ route("product.details", ":id") }}'.replace(':id', productId),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response && response.product) {
                        var product = response.product; // Product data returned by the server
                        var p_composition = JSON.parse(product.composition);
                        var p_usage = JSON.parse(product.usage);
                        var p_type = JSON.parse(product.type);
                        var p_design_type = JSON.parse(product.design_type);
                        var createdAt = new Date(product.created_at);
                        var pcreatedAt = createdAt.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
                        var updatedAt = new Date(product.updated_at);
                        var pupdatedAt = updatedAt.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });

                        // Populate the offcanvas with the fetched product details
                        $('#ProductViewLabel').text(product.tally_code || 'Product Details');
                        $('#product-type').text(product.product_type?.product_type || '-');
                        $('#product-code').text(product.tally_code || '-');
                        $('#file-number').text(product.file_number || '-');
                        $('#supplier-name').text(product.supplier?.name || '-');
                        $('#supplier-collection').text(product.supplier_collection?.collection_name || '-');
                        $('#supplier-design').text(product.supplier_collection_design?.design_name || '-');
                        $('#design-sku').text(product.design_sku || '-');
                        if (product.width) { $('#width').text(product.width); } 
                          else { $('#width').text('-');
                            $('#width').closest('tr').hide();
                        }
                        if (product.rubs_martendale) { $('#rubs-martendale').text(product.rubs_martendale); } 
                          else { $('#rubs-martendale').text('-');
                            $('#rubs-martendale').closest('tr').hide();
                        }
                        $('#usage').text(p_usage || '-');
                        $('#type').text(p_type || '-');
                        $('#design-type').text(p_design_type || '-');
                        $('#colour').text(product.colour || '-');
                        $('#composition').text(p_composition || '-');
                        $('#note').text(product.note || '-');
                        $('#created-at').text(pcreatedAt || '-');
                        $('#updated-at').text(pupdatedAt || '-');

                        // Handle the image
                        if (product.image) {
                            $('#image-gallery').html(`
                                <a href="${'/storage/' + product.image}" data-fancybox data-caption="${product.tally_code}">
                                    <img class="tableImage" src="${'/storage/' + product.image}" alt="${product.image_alt || 'Product Image'}" / style="max-width: 150px; height: auto;">
                                </a>
                            `);
                        } else {
                            $('#image-gallery').html('<p>No image available</p>');
                        }
                    } else {
                        alert('Product details not found.');
                    }
                },
                error: function() {
                    alert('Error loading product details.');
                }
            });
        });
    });

    const supplierId = '{{ request()->get('supplier_name') }}';
    const collectionId = '{{ request()->get('supplier_collection') }}';
    const designId = '{{ request()->get('supplier_collection_design') }}';

    // Pre-select the Supplier Name dropdown
    if (supplierId && supplierId !== 'Select') {
        $('#supplier_name').val(supplierId).trigger('change');
    }

    // Pre-select the Supplier Collection dropdown if a collection ID is available
    if (collectionId && collectionId !== 'Select') {
        $('#supplier_collection').val(collectionId).trigger('change');
    }

    // Pre-select the Supplier Collection Design dropdown if a design ID is available
    if (designId && designId !== 'Select') {
        $('#supplier_collection_design').val(designId);
    }

    // Handle Supplier Name Change
    $('#supplier_name').change(function() {
        const supplierId = $(this).val();

        // Clear the dependent dropdowns
        $('#supplier_collection').empty().append('<option value="Select">Select Supplier Collection</option>');
        $('#supplier_collection_design').empty().append('<option value="Select">Select Supplier Collection Design</option>');

        if (supplierId && supplierId !== 'Select') {
            // Fetch Supplier Collections based on selected Supplier
            $.ajax({
                url: `/supplier-collection/${supplierId}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.length === 0) {
                        $('#supplier_collection').append('<option value="" disabled>No collections found</option>');
                    } else {
                        data.forEach(item => {
                            $('#supplier_collection').append(`<option value="${item.id}" ${item.id === collectionId ? 'selected' : ''}>${item.collection_name}</option>`);
                        });
                    }
                },
                error: function() {
                    alert('Error retrieving collections');
                }
            });
        }
    });

    // Handle Supplier Collection Change
    $('#supplier_collection').change(function() {
        const collectionId = $(this).val();
        const supplierId = $('#supplier_name').val();

        // Clear the dependent dropdown
        $('#supplier_collection_design').empty().append('<option value="Select">Select Supplier Collection Design</option>');

        if (collectionId && supplierId) {
            // Fetch Supplier Collection Designs based on selected Supplier and Collection
            $.ajax({
                url: `/supplier-collection-designs/${supplierId}/${collectionId}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data.length === 0) {
                        $('#supplier_collection_design').append('<option value="" disabled>No designs found</option>');
                    } else {
                        data.forEach(item => {
                            $('#supplier_collection_design').append(`<option value="${item.id}" ${item.id === designId ? 'selected' : ''}>${item.design_name}</option>`);
                        });
                    }
                },
                error: function() {
                    alert('Error retrieving designs');
                }
            });
        }
    });
</script>
@endsection