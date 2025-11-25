@extends('layouts.admin.master')

@section('title', 'Bookings Management')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Bookings Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Bookings</li>
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
    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ panel_route('bookings.index') }}" class="row g-3">
                <div class="col-md-2">
                    <select name="amenity_id" class="form-control">
                        <option value="">All Amenities</option>
                        @foreach($amenities as $amenity)
                            <option value="{{ $amenity->id }}" {{ request('amenity_id') == $amenity->id ? 'selected' : '' }}>{{ $amenity->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="booking_date" class="form-control" value="{{ request('booking_date') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-borderless align-middle">
                <thead>
                    <tr>
                        <th>Booking #</th>
                        <th>Amenity</th>
                        <th>Unit</th>
                        <th>Resident</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="fw-semibold">{{ $booking->booking_number }}</td>
                            <td>{{ $booking->amenity->name }}</td>
                            <td>{{ $booking->unit->unit_number }}</td>
                            <td>{{ $booking->resident?->name ?? '-' }}</td>
                            <td>{{ $booking->booking_date->format('M d, Y') }}</td>
                            <td>{{ $booking->start_time?->format('h:i A') }} - {{ $booking->end_time?->format('h:i A') }}</td>
                            <td>
                                <span class="badge badge-{{ $booking->status == 'approved' ? 'success' : ($booking->status == 'rejected' ? 'danger' : ($booking->status == 'cancelled' ? 'secondary' : 'warning')) }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="text-end table-action-cell">
                                <div class="table-actions">
                                    <a href="{{ panel_route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-info"
                                        title="View booking {{ $booking->booking_number }}">
                                        <i data-feather="eye"></i>
                                        <span>View</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection

