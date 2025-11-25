@extends('layouts.admin.master')

@section('title', 'Booking Details')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Booking Details</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('bookings.index') }}">Bookings</a></li>
                    <li class="breadcrumb-item active">{{ $booking->booking_number }}</li>
                </ol>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Booking Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Booking Number:</th>
                            <td>{{ $booking->booking_number }}</td>
                        </tr>
                        <tr>
                            <th>Amenity:</th>
                            <td>{{ $booking->amenity->name }}</td>
                        </tr>
                        <tr>
                            <th>Unit:</th>
                            <td>{{ $booking->unit->unit_number }}</td>
                        </tr>
                        <tr>
                            <th>Resident:</th>
                            <td>{{ $booking->resident?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Booking Date:</th>
                            <td>{{ $booking->booking_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Time:</th>
                            <td>{{ $booking->start_time?->format('h:i A') }} - {{ $booking->end_time?->format('h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Amount:</th>
                            <td>₹{{ number_format($booking->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge badge-{{ $booking->status == 'approved' ? 'success' : ($booking->status == 'rejected' ? 'danger' : ($booking->status == 'cancelled' ? 'secondary' : 'warning')) }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                        @if($booking->special_requests)
                        <tr>
                            <th>Special Requests:</th>
                            <td>{{ $booking->special_requests }}</td>
                        </tr>
                        @endif
                        @if($booking->rejection_reason)
                        <tr>
                            <th>Rejection Reason:</th>
                            <td>{{ $booking->rejection_reason }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Actions</h5>
                </div>
                <div class="card-body">
                    @if($booking->status == 'pending')
                        <form action="{{ panel_route('bookings.approve', $booking) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">Approve Booking</button>
                        </form>
                        <form action="{{ panel_route('bookings.reject', $booking) }}" method="POST" id="rejectForm">
                            @csrf
                            <div class="mb-2">
                                <textarea name="rejection_reason" class="form-control" rows="3" placeholder="Rejection reason..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">Reject Booking</button>
                        </form>
                    @elseif($booking->status == 'approved')
                        <form action="{{ panel_route('bookings.cancel', $booking) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <textarea name="cancellation_reason" class="form-control" rows="3" placeholder="Cancellation reason..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning w-100">Cancel Booking</button>
                        </form>
                    @endif
                    @if($booking->approvedBy)
                        <hr>
                        <p><strong>Approved By:</strong> {{ $booking->approvedBy->name }}</p>
                        <p><strong>Approved At:</strong> {{ $booking->approved_at?->format('M d, Y h:i A') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

