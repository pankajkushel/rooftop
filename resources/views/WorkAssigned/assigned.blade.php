@extends('admin.layouts.app')

@section('title', 'Lead Management')

@section('content')

@if(session('success'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: @json(session('success')),
            confirmButtonText: 'OK'
        });
    });
</script>
@endif

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Work Mediator</h1>
        <button class="btn text-white shadow-sm" id="createLeadBtn" data-bs-toggle="modal" data-bs-target="#createLeadModal" style="background-color: #052c65">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create Worker Mediator 
        </button>
    </div>

    <!-- Create Lead Modal -->
    <div class="modal fade" id="createLeadModal" tabindex="-1" aria-labelledby="createLeadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Worker</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('assignedworks.store') }}" method="post" id="leadForm">
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
                                <label for="phone">Work Title</label>
                                <input type="tel" class="form-control" id="phone" name="Work_Title">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone">Work Description</label>
                                <input type="tel" class="form-control" id="phone" name="description">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone">Assigned Date</label>
                                <input type="date" class="form-control" id="phone" name="assigned_date">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone">Dead Line</label>
                                <input type="date" class="form-control" id="phone" name="Dead_Line">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone">Completion Date</label>
                                <input type="date" class="form-control" id="phone" name="CompletionDate">
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

    <!-- Leads Table -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">Work Mediator List</h6>
            <div class="input-group w-auto">
                <input type="text" class="form-control" id="searchInput" placeholder="Search leads...">
                <button class="btn btn-light" type="button"><i class="fas fa-search"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%">
                    <thead class="thead-light">
                        <tr>
                            <th>SR.</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Description</th>
                           
                            <th>Assigned Date</th>
                            <th>Deadline</th>
                            <th>Completion Date</th>
                        </t<r>
                    </thead>
                    <tbody>
                        @foreach ($workAssigned as $lead)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $lead->full_name }}</td>
                            <td>{{ $lead->email }}</td>
                            <td>{{ $lead->phone }}</td>
                            {{-- <td>
                                @if($lead->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($lead->status == 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @elseif($lead->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-secondary">Unknown</span>
                                @endif
                            </td> --}}
                            
                            <td>{{ $lead->task_description }}</td>
                            <td>{{ $lead->assigned_date }}</td>
                            <td>{{ $lead->deadline }}</td>
                            <td>{{ $lead->completion_date }}</td>
                            {{-- <td class="text-center position-relative">
                                <button class="btn btn-sm p-0 border-0 bg-transparent toggle-options" data-id="{{ $lead->id }}">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="position-absolute bg-white shadow rounded p-2 d-none options-menu" id="options-{{ $lead->id }}"
                                    style="top: 100%; left: 50%; transform: translateX(-50%); min-width: 180px; z-index: 1000;">
                                    
                                    <a href="{{ route('leadEdit', $lead->id) }}" class="btn btn-sm btn-light w-100 text-start mb-1">
                                        <i class="fas fa-edit me-2 text-primary"></i> Edit
                                    </a>
                                    <a href="{{ route('leadDelete', $lead->id) }}"
                                       onclick="return confirm('Are you sure you want to delete this lead?')"
                                       class="btn btn-sm btn-light w-100 text-start mb-1 text-danger">
                                        <i class="fas fa-trash me-2"></i> Delete
                                    </a>
                                    @if(!$lead->is_converted)
                                        <form action="{{ route('leads.convert', $lead->id) }}" method="POST" class="d-block mb-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light w-100 text-start">
                                                <i class="fas fa-user-plus me-2 text-success"></i> Add to Customer
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-light w-100 text-start" disabled>
                                            <i class="fas fa-check me-2 text-success"></i> Customer Created
                                        </button>
                                    @endif
                                </div>
                            </td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Add pagination if needed -->
            </div>
        </div>
    </div>
</div>

{{-- Three-dot dropdown logic --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const buttons = document.querySelectorAll('.toggle-options');
        buttons.forEach(btn => {
            btn.addEventListener('click', function (e) {
                const id = this.getAttribute('data-id');
                const menu = document.getElementById(`options-${id}`);

                // Close other open menus
                document.querySelectorAll('.options-menu').forEach(m => {
                    if (m !== menu) m.classList.add('d-none');
                });

                // Toggle current menu
                menu.classList.toggle('d-none');

                // Close on outside click
                document.addEventListener('click', function outsideClickListener(event) {
                    if (!menu.contains(event.target) && !btn.contains(event.target)) {
                        menu.classList.add('d-none');
                        document.removeEventListener('click', outsideClickListener);
                    }
                });
            });
        });
    });
</script>

@endsection
