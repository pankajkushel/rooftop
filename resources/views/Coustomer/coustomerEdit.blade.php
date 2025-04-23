@extends('admin.layouts.app')

@section('title', 'Edit Customer')
@section('content')

<style>
    body {
        backdrop-filter: blur(5px);
        background-color: rgba(0,0,0,0.4);
    }
    .modal-style {
        max-width: 800px;
        margin: 2rem auto;
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.5);
    }
</style>

<div class="modal-style">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Edit Customer</h3>
        <a href="{{ url()->previous() }}" class="btn btn-close"></a>
    </div>

    <form action="{{ route('coustomerUpdate', $coustomer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name">Full Name</label>
                <input type="text" name="name" value="{{ $coustomer-> }}" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" value="{{ $coustomer->email }}" class="form-control">
            </div>
          
            <div class="col-md-6 mb-3">
                <label for="phone">Phone</label>
                <input type="tel" name="phone" value="{{ $coustomer->phone }}" class="form-control">
            </div>

          

            <div class="col-md-6 mb-3">
                <label for="address">Address</label>
                <input type="text" name="address" value="{{ $coustomer->address }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Gender</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male" {{ $coustomer->gender == 'Male' ? 'checked' : '' }}>
                    <label class="form-check-label" for="genderMale">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female" {{ $coustomer->gender == 'Female' ? 'checked' : '' }}>
                    <label class="form-check-label" for="genderFemale">Female</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="genderOther" value="Other" {{ $coustomer->gender == 'Other' ? 'checked' : '' }}>
                    <label class="form-check-label" for="genderOther">Other</label>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="dob">Date of Birth</label>
                <input type="date" name="dob" value="{{ $coustomer->dob }}" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label for="coustomer_type">Customer Type</label>
                <select name="coustomer_type" class="form-select">
                    <option value="">Select Type</option>
                    <option value="New" {{ $coustomer->coustomer_type == 'New' ? 'selected' : '' }}>New</option>
                    <option value="Regular" {{ $coustomer->coustomer_type == 'Regular' ? 'selected' : '' }}>Regular</option>
                    <option value="VIP" {{ $coustomer->coustomer_type == 'VIP' ? 'selected' : '' }}>VIP</option>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-3 gap-2">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Customer</button>
        </div>
    </form>
</div>

@endsection