@extends('admin.layouts.app')

@section('title', 'Customers List')

@section('content')

<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0">All Customers <span class="fw-normal text-muted">({{ $totalCustomer }})</span></h6>
        <!-- <a href="{{ route('leadcreate') }}" class="primary-btn addBtn">+ Add
            Lead</a> -->
            <!-- <button class="btn text-white shadow-sm" id="createLeadBtn" data-bs-toggle="modal" data-bs-target="#createLeadModal" style="background-color: #052c65">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create Lead
        </button> -->
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
                        <th>Source</th>
                        <th>Address</th>        
                        
                      
                        <th>Assign To </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($customerdata as $customers)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{$customers->full_name}}</td>
                        <td>{{$customers->email}}</td>
                        <td>{{$customers->phone}}</td>
                        <td>{{$customers->source}}</td>
                        <td>{{ \Illuminate\Support\Str::words($customers->address, 5, '...') }}</td>
                       
                       
                        <td>{{$customers->assigned_to || 'N/A' }}</td>
                        <td>
                        <div class="dropdown">
                                <i class="bi bi-three-dots-vertical" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false"></i>
                                <ul class="dropdown-menu">
                                    
                                    <li>
                                        <a href="javascript::void(0)" class="dropdown-item small viewProductLink" id="open-customer-details-{{ $customers->id }}" data-id="{{ $customers->id }}">
                                            View
                                        </a>
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

    <!-- View Detail -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="CustomerView" aria-labelledby="CustomerViewLabel">
    <div class="offcanvas-header">
        <h5 id="CustomerViewLabel">Customer Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <table class="table table-bordered">
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function () {
        $(document).on('click', '[id^="open-customer-details-"]', function () {
            var customerId = $(this).data('id');
           
            $.ajax({
                url: '/customer/details/' + customerId,
                method: 'GET',
                success: function (response) {
                    if (response.status === 'success') {
                        var customer = response.data;

                        $('#CustomerViewLabel').text(customer.full_name);
                        $('#CustomerView .offcanvas-body table tbody').html(`
                            <tr><th>Name</th><td>${customer.full_name}</td></tr>
                            <tr><th>Email</th><td>${customer.email || 'N/A'}</td></tr>
                            <tr><th>Phone</th><td>${customer.phone || 'N/A'}</td></tr>
                            <tr><th>Address</th><td>${customer.address || 'N/A'}</td></tr>
                        `);

                        $('#CustomerView').offcanvas('show');
                    } else {
                        alert('Customer not found.');
                    }
                },
                error: function () {
                    alert('Something went wrong.');
                }
            });
        });
    });
</script>
@endsection