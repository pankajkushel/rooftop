@extends('admin.layouts.app')

@section('title', 'Customert Appointment List')

@section('content')
<div class="dataOverviewSection mt-3">
    <div class="section-title">
        <h6 class="fw-bold m-0">All Query Booked 
            <span class="fw-normal text-muted">({{ count($appointments) }})</span>
        </h6>
        <a href="{{ route('export.book.query') }}">Export</a>
    </div>

    <div class="dataOverview mt-3">
        <div class="table-responsive">
            <table class="table" id="projectsTable">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Pincode</th>
                        <th>City</th>
                        <th>State</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $key => $appointment)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $appointment->name }}</td>
                        <td>{{ $appointment->email }}</td>
                        <td>{{ $appointment->mobile }}</td>
                        <td>{{ $appointment->pincode }}</td>
                        <td>{{ $appointment->city }}</td>
                        <td>{{ $appointment->state }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection

@section('script')

@endsection