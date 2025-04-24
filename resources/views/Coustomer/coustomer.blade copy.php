@extends('admin.layouts.app')

@section('title', 'Lead Management')
@section('content')

@push('scripts')
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<!-- DataTables CDN -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#leadsTable').DataTable({
            "searching": true,  // Ensure the search bar is enabled
            "paging": true,  // Enable pagination
            "pageLength": 10, // Default number of records per page
            "lengthChange": true, // Allow length change (select number of rows)
            "ordering": true, // Enable sorting
            "info": true,  // Show table info
            "autoWidth": false, // Disable auto-width calculation
        });
    });
</script>
@endpush

<head>
    <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

</head>

{{-- //sweeter  --}}

@if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Customer Details...</h1>
        <button class="btn text-white shadow-sm" id="createLeadBtn" data-bs-toggle="modal" data-bs-target="#createLeadModal" style="background-color: #052c65">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add Customer
        </button>
    </div>

    <!-- Create Lead Modal -->
    <div class="modal fade" id="createLeadModal" tabindex="-1" aria-labelledby="createLeadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header ">
                    <h5 class="modal-title" id="createLeadModalLabel">Add New Customer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="leadForm" action="{{ route('coustomer.Create') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="full_name" name="name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-md-6">
                                <label for="editAddress" class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" id="editAddress">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="editMale" value="Male">
                                        <label class="form-check-label" for="editMale">
                                            Male
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="editFemale" value="Female">
                                        <label class="form-check-label" for="editFemale">
                                            Female
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="editOther" value="Other">
                                        <label class="form-check-label" for="editOther">
                                            Other
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="editDob" class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" id="editDob">
                            </div>

                            <div class="col-md-6">
                                <label for="editCustomerType"  class="form-label">Customer Type</label>
                                <select class="form-select" name="customer_type" id="editCustomerType">
                                    <option value="">Select Type</option>
                                    <option value="New"> New</option>
                                    <option value="Regular">Regular</option>
                                    <option value="VIP">VIP</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end border-top pt-3">
                            <button type="reset" class="btn btn-secondary me-2">Reset</button>
                            <button type="submit" class="btn text-white" style="background-color: #052c65">Add Customer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leads Table Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold">Leads List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="leadsTable" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>SR.</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Source</th>
                        <th>Address</th>        
                        <th>Gender</th>
                        
                      
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
                        <td>{{$customers->address}}</td>
                        <td>{{$customers->gender}}</td>
                       
                       
                        <td>{{$customers->registration_date}}</td>
                        <td>
                            <a href="{{ route('coustomerEdit', $customers->id) }}" class="btn btn-sm btn-circle btn-outline-success mr-1 edit-lead">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
