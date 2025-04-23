@extends('admin.layouts.app')

@section('title', 'Customert Appointment List')

@section('content')
<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0">All Quotations <span class="fw-normal text-muted">({{ count($quotationList) }})</span></h6>
        
    </div>

    <div class="dataOverview mt-3">
        <div>
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">

                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-pending-tab" data-bs-toggle="pill" data-bs-target="#pills-pending" type="button" role="tab" aria-controls="pills-pending" aria-selected="false">Hold <span class="fw-normal small">({{$quotationListPending}})</span></button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link " id="pills-complete-tab" data-bs-toggle="pill" data-bs-target="#pills-complete" type="button" role="tab" aria-controls="pills-complete" aria-selected="false">Completed <span class="fw-normal small">({{$quotationListComplete}})</span></button>
                </li>
            </ul>
        </div>

        <!-- all data view -->

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-pending" role="tabpanel" aria-labelledby="pills-pending-tab" tabindex="0">
                <!-- This content will be dynamically populated -->
            </div>

            <div class="tab-pane fade" id="pills-assign" role="tabpanel" aria-labelledby="pills-assign-tab" tabindex="0">
                <!-- This content will be dynamically populated -->
            </div>

            <div class="tab-pane fade" id="pills-complete" role="tabpanel" aria-labelledby="pills-complete-tab" tabindex="0">
                <!-- This content will be dynamically populated -->
            </div>

            <div class="tab-pane fade" id="pills-rejected" role="tabpanel" aria-labelledby="pills-rejected-tab" tabindex="0">
                <!-- This content will be dynamically populated -->
            </div>


            <!-- View franchise details Offcanvas -->

            <div class="offcanvas FranciseViewSidebar offcanvas-start" tabindex="-1" id="FranciseView" aria-labelledby="FranciseViewLabel">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title fw-bold" id="FranciseViewLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <table class="table table-hover">
                        <tbody></tbody>
                    </table>
                </div>
                
            </div>
        </div>

        <div class="table-responsive">
            <table class="table" id="projectsTable">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Quotation ID</th>
                        <th>Date</th>
                        <th>Client Name</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded here dynamically based on the active tab -->
                </tbody>
            </table>
        </div>

        <!-- end all data view -->



    </div>
</div>


<!-- Reject Modal -->
<div class="modal fade" id="rejectFranchiseModal" tabindex="-1" aria-labelledby="rejectFranchiseModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="rejectFranchiseForm" method="POST"
                action="{{ route('quotation.delete', ['id' => '__appointment_id__']) }}" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="rejectFranchiseModalLabel">Delete Quotation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this quotation?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="secondary-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="primary-btn">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Franchise Modal Start -->
<div class="modal fade" id="addFranchiseModal1" tabindex="-1" aria-labelledby="addFranchiseModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Add Franchise</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('franchise_temp.store_admin') }}" method="POST" id="contact-form1">
                @csrf
                <div class="modal-body">

                    <div class="mb-3 w-100">
                        <label for="NameInput" class="form-label mb-1">Name<span class="requried">*</span></label>
                        <input type="text" class="form-control w-100" id="name" name="name">
                    </div>
                    <div class="mb-3 w-100">
                        <label for="UserEmailInput" class="form-label mb-1">Email ID<span
                                class="requried">*</span></label>
                        <input type="email" class="form-control w-100" id="email" name="email">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 w-100">
                                <label for="contactNumberInput" class="form-label mb-1">Contact Number<span
                                        class="requried">*</span></label>
                                <input type="number" class="form-control w-100" id="mobile" name="mobile">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 w-100">
                                <label for="altcontactNumberInput" class="form-label mb-1">Alternate Contact
                                    Number</label>
                                <input type="number" class="form-control w-100" id="alt_mobile" name="alt_mobile">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3 w-100">
                                <label for="registerationType" class="form-label">Registration Type<span
                                        class="requried">*</span></label>
                                <select name="registerationType" class="form-control form-select w-100" id="registerationType">
                                    <option value="">Select Registration Type</option>
                                    <option value="Individual">Individual</option>
                                    <option value="Company">Company</option>
                                    <option value="proprietor">Proprietor</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 w-100">
                                <label for="company_name" class="form-label">Company Name <span
                                        class="requried">*</span></label>
                                <input type="text" class="form-control w-100" id="company_name" name="company_name"
                                    placeholder="Enter Company Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 w-100">
                                <label for="employees" class="form-label">Number of Employees<span
                                        class="requried">*</span></label>
                                <input type="number" class="form-control w-100" id="employees" name="employees"
                                    placeholder="Enter Number of Employees">
                            </div>
                        </div>

                    </div>

                    <div class="mb-3 w-100">
                        <label for="franchiseAddress" class="form-label mb-1">Address<span
                                class="requried">*</span></label>
                        <textarea name="address" class="form-control w-100" id="address" name="address"
                            style="height: 70px;"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 w-100">
                                <label for="pincodeInput" class="form-label mb-1">Pincode<span
                                        class="requried">*</span></label>
                                <input type="number" class="form-control w-100" id="pincode" name="pincode">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 w-100">
                                <label for="countryInput" class="form-label mb-1"> Country<span
                                        class="requried">*</span></label>
                                <input type="text" class="form-control w-100" id="country" name="country">

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 w-100">
                                <label for="countryInput" class="form-label mb-1"> State<span
                                        class="requried">*</span></label>
                                <input type="text" class="form-control w-100" id="state" name="state">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 w-100">
                                <label for="countryInput" class="form-label mb-1"> City<span
                                        class="requried">*</span></label>
                                <input type="text" class="form-control w-100" id="city" name="city">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="secondary-btn addBtn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="primary-btn addBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Franchise Modal End -->

<!-- Approval Modal -->
<div class="modal fade" id="assignAppointmentModal" tabindex="-1" aria-labelledby="approveFranchiseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="approveFranchiseModalLabel">Approve Franchise</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="appointmentId" name="appointment_id">
                    <div class="mb-3">
                        <label for="franchise" class="form-label">Select Franchise<span class="requried">*</span></label>
                        <select id="franchise" name="franchise_id" class="form-select w-100" required>
                            <option value="">Select Franchise</option>
                     
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Appointment Date</label>
                        <input type="datetime-local" name="dateFilter" id="dateFilter" placeholder="Filter by date" value="{{ request('dateFilter') }}"
                            class="form-control me-3 w-100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="secondary-btn" data-bs-dismiss="modal">Cancel</button>
                    <!-- <button type="submit" class="primary-btn">Assign</button> -->
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Franchise Modal End -->

@endsection

@section('script')

<script>
    
    function confirmAssign(appointmentId) {
        // Open modal and set appointment ID
        $('#assignAppointmentModal').modal('show');
        $('#appointmentId').val(appointmentId);
    }

    // function rejected(appointmentId) {
    //     // Open modal and set appointment ID
    //     $('#rejectFranchiseModal').modal('show');
    //     $('#appointmentId').val(appointmentId);
    // }

    $(document).ready(function() {
        // Initial load for the 'pending' tab data
        loadQuotationData('pending');

        // Handle tab change event
        $('#pills-tab button').on('click', function() {
            var tabId = $(this).attr('id').split('-')[1]; // Extract tab ID (e.g., pending, assign, etc.)
            loadQuotationData(tabId); // Load data based on clicked tab
        });

        // Function to load appointment data based on the selected status
        function loadQuotationData(status) {
            //console.log(status);
            // Show loading indicator (optional)
            $('#projectsTable tbody').html('<tr><td colspan="10" class="text-center">Loading...</td></tr>');

            // AJAX call to fetch data from the server
            $.ajax({
                url: '/quotations/data', // API endpoint to fetch the data
                method: 'GET',
                data: {
                    status: status // Pass the selected tab status to the server
                },
                success: function(response) {
                    // Clear the current table rows before appending new data
                    $('#projectsTable tbody').empty();

                    // Check if response contains data and populate the table
                    if (response.data && response.data.length > 0) {
                        console.log(response.data);
                        $.each(response.data, function(idx, appnt) {
                            var row = '<tr>';
                            row += '<td>' + (idx + 1) + '</td>';
                            row += '<td>' + appnt.id + '</td>';
                            row += '<td>' + (appnt.date ? new Date(appnt.date).toLocaleDateString('en-GB').replace(/\//g, '-') : 'N/A') + '</td>';
                            row += '<td>' + appnt.franchise.name + '</td>';
                            row += '<td>' + appnt.address + '</td>';
                            
                            var statusBadge = '';
                            var viewType = '';
                            var actions = ''; // Store the actions that should be available
                            //alert(appnt.status);
                            switch (appnt.status) {
                                case '0':
                                    viewType = 'pending';
                                    statusBadge = '<span class="badge badge-pending">Pending</span>';
                                    actions = '<li><a href="javascript:" id="open-quotation-details-' + appnt.id + '" class="dropdown-item" data-id="' + appnt.id + '" data-checkType="' + viewType + '">Edit</a></li>';
                                    actions = '<li><a href="javascript:" id="open-quotation-details-' + appnt.id + '" class="dropdown-item" data-id="' + appnt.id + '" data-checkType="' + viewType + '">View</a></li>';
                                    actions += '<li><a href="quotations/download_quotes/' + appnt.id + '" class="dropdown-item small download_quotation_btn" data-quotation-id="' + appnt.id + '" >Download Quotation</a></li>';
                                    // actions += '<li><a href="javascript:" class="dropdown-item small approve-quotation-btn" data-quotation-id="' + appnt.id + '" onclick="showRejectAppointmenteModal(\'' + appnt.id + '\')">Delete</a></li>';
                                    break;
                                case '1':
                                    viewType = 'complete';
                                    statusBadge = '<span class="badge badge-active">Completed</span>';
                                    // actions = '<li><a href="javascript:" id="open-quotation-details-' + appnt.id + '" class="dropdown-item" data-id="' + appnt.id + '" data-checkType="' + viewType + '">Edit</a></li>';
                                    actions = '<li><a href="javascript:" id="open-quotation-details-' + appnt.id + '" class="dropdown-item" data-id="' + appnt.id + '" data-checkType="' + viewType + '">View</a></li>';
                                    // actions += '<li><a href="quotations/download_quotes/' + appnt.id + '" class="dropdown-item small download_quotation_btn" data-quotation-id="' + appnt.id + '" >Download Quotation</a></li>';
                                    // actions += '<li><a href="javascript:" class="dropdown-item small approve-quotation-btn" data-quotation-id="' + appnt.id + '" onclick="showRejectAppointmenteModal(\'' + appnt.id + '\')">Delete</a></li>';
                                    break;
                                default:
                                    viewType = 'pending';
                                    statusBadge = '<span class="badge badge-unknown">Unknown</span>';
                                    actions = ''; // Default to no actions
                                    break;
                            }

                            row += '<td><div><i class="bi bi-three-dots-vertical" type="button" data-bs-toggle="dropdown" aria-expanded="false"></i>';
                            row += '<ul class="dropdown-menu">';
                            // Only add the actions if there are any
                            if (actions) {
                                row += actions;
                            }
                            row += '</ul></div></td>';
                            row += '</tr>';
							//alert(row);
                            $('#projectsTable tbody').append(row);

                            
                        });
                        //$('#projectsTable').DataTable().clear().destroy();
                            //$('#projectsTable').DataTable()
                    } else {
                        $('#projectsTable tbody').html('<tr><td colspan="10" class="text-center">No data found</td></tr>');
                    }
                },
                error: function() {
                    // Handle AJAX request error
                    alert('Error loading franchise data');
                    $('#projectsTable tbody').html('<tr><td colspan="10" class="text-center">Failed to load data</td></tr>');
                }
            });
        }
    });

    $(document).on('click', '.download_quotation_btn', function(e) {
        e.preventDefault(); // Prevents default action (optional)

        // Get the href attribute, which contains the URL
        var url = $(this).attr('href');

        // Navigate to the URL using window.location
        window.location.href = url;
    });
    function showApproveFranchiseModal(franchiseId) {
        var form = document.getElementById('approveFranchiseForm');
        var actionUrl = form.action.replace('__franchise_id__', franchiseId); // Replace the placeholder with the actual franchise ID
        form.action = actionUrl; // Update the form action URL

        $('#approveFranchiseModal').modal('show');
    }

    $(document).on('click', '.approve-quotation-btn', function(e) {
        e.preventDefault();
        var franchiseId = $(this).data('quotation-id'); // Get the franchise ID from the button's data attribute
        showApproveFranchiseModal(franchiseId); // Trigger the modal
    });

    function showRejectAppointmenteModal(franchiseId) {
        var form = document.getElementById('rejectFranchiseForm');
        var actionUrl = form.action.replace('__appointment_id__', franchiseId); // Replace the placeholder with the actual franchise ID
        form.action = actionUrl; // Update the form action URL

        $('#rejectFranchiseModal').modal('show');
    }

    $(document).on('click', '.reject-franchise-btn', function(e) {
        e.preventDefault();
        var franchiseId = $(this).data('quotation-id'); // Get the franchise ID from the button's data attribute
        showRejectAppointmenteModal(franchiseId); // Trigger the modal
    });

    $(document).ready(function() {
        $(document).on('click', '[id^="open-quotation-details-"]', function() {
            
            var quotationId = $(this).data('id'); // Get quotation ID dynamically
            var quotationType = $(this).data('checktype'); // Get quotation ID dynamically

            // Ajax request to get quotation details
            $.ajax({
                url: '/quotations/details/' + quotationId + '/' + quotationType, // Make sure the URL is correct
                method: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        var quotation = response.data.quotation;
                        var appointment = response.data.appointment;
                        console.log(quotation);
                        // Populate table data dynamically
                        $('#FranciseViewLabel').text(quotation.name);

                        $('#FranciseView .offcanvas-body table tbody').html(`
                                <tr><th>Name</th><td>${quotation.name || 'N/A'}</td></tr>
                                <tr><th>Email ID</th><td>${quotation.email || 'N/A'}</td></tr>
                                <tr><th>Contact Number</th><td>${quotation.number || 'N/A'}</td></tr>
                                <tr><th>Date</th><td>${quotation.date ? new Date(quotation.date).toLocaleDateString('en-GB').replace(/\//g, '-') : 'N/A'}</td></tr>
                                <tr><th>Quotation For</th><td>${quotation.quot_for || 'N/A'}</td></tr>
                                <tr><th>Cartage</th><td>${quotation.cartage || 'N/A'}</td></tr>
                                <tr><th>Address</th><td>${quotation.address || 'N/A'}</td></tr>
                                <tr><th>City</th><td>${appointment.city || 'N/A'}</td></tr>
                                <tr><th>State</th><td>${appointment.state || 'N/A'}</td></tr>
                                <tr><th>Pincode</th><td>${appointment.pincode || 'N/A'}</td></tr>
                                <tr><th>Country</th><td>${appointment.country || 'N/A'}</td></tr>
                                
                            `);


                        // Show the off-canvas modal
                        $('#FranciseView').offcanvas('show');
                    } else {
                        alert('Franchise details not found.');
                    }
                },
                error: function() {
                    alert('An error occurred while fetching the data.');
                }
            });
        });

        $(".dt-responsive").dataTable({
            responsive: true,
            columnDefs: [{
                    responsivePriority: 1,
                    targets: 0
                },
                {
                    responsivePriority: 2,
                    targets: -1
                }
            ]
        });
        $(".dt-responsive1").dataTable({
            responsive: true,
            columnDefs: [{
                    responsivePriority: 1,
                    targets: 0
                },
                {
                    responsivePriority: 2,
                    targets: -1
                }
            ]
        });
    });

    $('#addFranchiseModal1').on('shown.bs.modal', function() {
        $("#contact-form1").validate({
            rules: {
                company_name: "required",
                name: "required",
                email: {
                    required: true,
                    email: true
                },
                mobile: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 15
                },
                alt_mobile: {
                    digits: true,
                    minlength: 10,
                    maxlength: 15
                },
                employees: {
                    required: true,
                    digits: true
                },
                address: "required",
                pincode: {
                    required: true,
                    digits: true,
                    minlength: 6,
                    maxlength: 6
                },
                city: "required",
                state: "required",
                country: "required"
            },
            messages: {
                company_name: "Please enter your company name",
                name: "Please enter your name",
                email: {
                    required: "Please enter your email",
                    email: "Please enter a valid email address"
                },
                mobile: {
                    required: "Please enter your mobile number",
                    digits: "Please enter a valid mobile number"
                },
                employees: "Please enter the number of employees",
                address: "Please enter your address",
                pincode: {
                    required: "Please enter your pincode",
                    digits: "Please enter a valid pincode"
                },
                city: "Please enter your city",
                state: "Please enter your state",
                country: "Please enter your country"
            },
            errorElement: "div", // Use div to display errors
            errorPlacement: function(error, element) {
                error.addClass("form-text text-danger xsmall");
                error.insertAfter(element); // Place the error directly after the input element
            },
            highlight: function(element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function(element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            }
        });
    });

    var input = document.querySelector("#mobile");
    var iti = "";
    // store the instance variable so we can access it in the console e.g. window.iti.getNumber()
    window.iti = iti;

    function saveData() {
        const formData = {
            company_name: $("#company_name").val(), // Match the name attribute in the input
            name: $("#name").val(),
            email: $("#email").val(),
            mobile: $("#mobile").val(),
            alt_mobile: $("#alt_mobile").val(),
            employees: $("#employees").val(),
            address: $("#address").val(),
            pincode: $("#pincode").val(),
            city: $("#city").val(),
            state: $("#state").val(),
            country: $("#country").val(),
            _token: $("input[name='_token']").val(), // CSRF token
        };

        $.ajax({
            type: "POST",
            url: "{{ route('franchise_temp.store_admin') }}", // Your server-side script to save data
            data: formData,
            success: function(response) {
                //alert('ddd');
                document.getElementById('contact-form1').style.display = 'none'; // Hide all form fields
                document.getElementById('form-title1').style.display = 'none';
                document.getElementById('thankYouMessage1').style.display = 'block'; // Show the thank you message
                $("#contact-form")[0].reset(); // Reset the form after successful submission
            },
            error: function(xhr, status, error) {
                alert("An error occurred while saving data: " + error);
            }
        });
    }


    document.addEventListener("DOMContentLoaded", function() {
        const registrationType = document.getElementById("registerationType");
        const companyNameField = document.getElementById("company_name").parentElement;
        const employeesField = document.getElementById("employees").parentElement;

        // Initially hide the fields
        companyNameField.style.display = "none";
        employeesField.style.display = "none";

        // Add event listener for change on Registration Type field
        registrationType.addEventListener("change", function() {
            const selectedValue = registrationType.value;

            if (selectedValue === "Company" || selectedValue === "proprietor") {
                companyNameField.style.display = "block";
                employeesField.style.display = "block";
            } else {
                companyNameField.style.display = "none";
                employeesField.style.display = "none";
            }
        });
    });
</script>
@endsection