@extends('admin.layouts.app')

@section('title', 'Edit Lead')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Lead</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('leadUpdate', $lead->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="{{ $lead->full_name }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $lead->email }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="{{ $lead->phone }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="source" class="form-label">Source</label>
                        <select class="form-control" id="source" name="source">
                            <option value="">Select Source</option>
                            <option value="Website" {{ $lead->source == 'Website' ? 'selected' : '' }}>Website</option>
                            <option value="Referral" {{ $lead->source == 'Referral' ? 'selected' : '' }}>Referral</option>
                            <option value="Social Media" {{ $lead->source == 'Social Media' ? 'selected' : '' }}>Social Media</option>
                            <option value="Advertisement" {{ $lead->source == 'Advertisement' ? 'selected' : '' }}>Advertisement</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="assigned_to" class="form-label">Assigned To</label>
                    <select class="form-control" id="assigned_to" name="assigned_to">
                        <option value="">Select Agent</option>
                        <option value="1" {{ $lead->assigned_to == '1' ? 'selected' : '' }}>John Doe</option>
                        <option value="2" {{ $lead->assigned_to == '2' ? 'selected' : '' }}>Jane Smith</option>
                        <option value="3" {{ $lead->assigned_to == '3' ? 'selected' : '' }}>Mike Johnson</option>
                    </select>
                </div>  
                <div class="col-md-6 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" style="background-color:
                        {{ $lead->status == 'pending' ? '#fff3cd' : 
                           ($lead->status == 'rejected' ? '#f8d7da' : 
                           ($lead->status == 'completed' ? '#d4edda' : 'white')) }};
                        color:
                        {{ $lead->status == 'pending' ? '#856404' : 
                           ($lead->status == 'rejected' ? '#721c24' : 
                           ($lead->status == 'completed' ? '#155724' : 'black')) }};">
                        <option value="">Select Status</option>
                        <option value="pending" {{ $lead->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ $lead->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="completed" {{ $lead->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3">{{ $lead->address }}</textarea>
                </div>

                <div class="d-flex justify-content-end border-top pt-3">
                    <a href="{{ route('leadIndex') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn text-white" style="background-color: #052c65">Update Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
