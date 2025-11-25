@extends('layouts.admin.master')

@section('title', 'Edit Amenity')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Edit Amenity</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('amenities.index') }}">Amenities</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ panel_route('amenities.update', $amenity) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name', $amenity->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Booking Fee <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="booking_fee" class="form-control @error('booking_fee') is-invalid @enderror" 
                                value="{{ old('booking_fee', $amenity->booking_fee) }}" required>
                            @error('booking_fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Max Advance Booking Days <span class="text-danger">*</span></label>
                            <input type="number" name="max_advance_booking_days" class="form-control @error('max_advance_booking_days') is-invalid @enderror" 
                                value="{{ old('max_advance_booking_days', $amenity->max_advance_booking_days) }}" required>
                            @error('max_advance_booking_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Min Advance Booking Hours <span class="text-danger">*</span></label>
                            <input type="number" name="min_advance_booking_hours" class="form-control @error('min_advance_booking_hours') is-invalid @enderror" 
                                value="{{ old('min_advance_booking_hours', $amenity->min_advance_booking_hours) }}" required>
                            @error('min_advance_booking_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Opening Time</label>
                            <input type="time" name="opening_time" class="form-control" value="{{ old('opening_time', $amenity->opening_time?->format('H:i')) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Closing Time</label>
                            <input type="time" name="closing_time" class="form-control" value="{{ old('closing_time', $amenity->closing_time?->format('H:i')) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Max Booking Duration (Hours)</label>
                            <input type="number" name="max_booking_duration_hours" class="form-control" value="{{ old('max_booking_duration_hours', $amenity->max_booking_duration_hours) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="requires_approval" id="requires_approval" 
                                    {{ old('requires_approval', $amenity->requires_approval) ? 'checked' : '' }}>
                                <label class="form-check-label" for="requires_approval">Requires Approval</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" 
                                    {{ old('is_active', $amenity->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $amenity->description) }}</textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Terms and Conditions</label>
                            <textarea name="terms_and_conditions" class="form-control" rows="4">{{ old('terms_and_conditions', $amenity->terms_and_conditions) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <a href="{{ panel_route('amenities.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Amenity</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

