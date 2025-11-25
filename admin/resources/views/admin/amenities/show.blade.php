@extends('layouts.admin.master')

@section('title', 'Amenity Details')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Amenity Details</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('amenities.index') }}">Amenities</a></li>
                    <li class="breadcrumb-item active">{{ $amenity->name }}</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ panel_route('amenities.edit', $amenity) }}" class="btn btn-primary">
                    <i data-feather="edit" class="me-2"></i>Edit Amenity
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Amenity Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Name:</th>
                            <td>{{ $amenity->name }}</td>
                        </tr>
                        <tr>
                            <th>Booking Fee:</th>
                            <td>₹{{ number_format($amenity->booking_fee, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Max Advance Booking:</th>
                            <td>{{ $amenity->max_advance_booking_days }} days</td>
                        </tr>
                        <tr>
                            <th>Min Advance Booking:</th>
                            <td>{{ $amenity->min_advance_booking_hours }} hours</td>
                        </tr>
                        <tr>
                            <th>Opening Time:</th>
                            <td>{{ $amenity->opening_time?->format('h:i A') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Closing Time:</th>
                            <td>{{ $amenity->closing_time?->format('h:i A') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Requires Approval:</th>
                            <td>
                                <span class="badge badge-{{ $amenity->requires_approval ? 'warning' : 'success' }}">
                                    {{ $amenity->requires_approval ? 'Yes' : 'No' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge badge-{{ $amenity->is_active ? 'success' : 'secondary' }}">
                                    {{ $amenity->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        @if($amenity->description)
                        <tr>
                            <th>Description:</th>
                            <td>{{ $amenity->description }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Bookings</h5>
                </div>
                <div class="card-body">
                    @forelse($amenity->bookings->take(5) as $booking)
                        <div class="mb-2 p-2 border rounded">
                            <strong>{{ $booking->booking_number }}</strong><br>
                            <small>{{ $booking->unit->unit_number }} - {{ $booking->booking_date->format('M d, Y') }}</small>
                        </div>
                    @empty
                        <p class="text-muted">No bookings yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

