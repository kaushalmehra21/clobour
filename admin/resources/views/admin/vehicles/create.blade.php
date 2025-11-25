@extends('layouts.admin.master')

@section('title', 'Register Vehicle')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Register Vehicle</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('vehicles.index') }}">Vehicles</a></li>
                    <li class="breadcrumb-item active">Register</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ panel_route('vehicles.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Unit <span class="text-danger">*</span></label>
                            <select name="unit_id" id="unit_id" class="form-control @error('unit_id') is-invalid @enderror" required>
                                <option value="">Select Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->unit_number }}</option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Resident <span class="text-danger">*</span></label>
                            <select name="resident_id" id="resident_id" class="form-control @error('resident_id') is-invalid @enderror" required>
                                <option value="">Select Resident</option>
                            </select>
                            @error('resident_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                            <input type="text" name="vehicle_number" class="form-control @error('vehicle_number') is-invalid @enderror" 
                                value="{{ old('vehicle_number') }}" required>
                            @error('vehicle_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                            <input type="text" name="vehicle_type" class="form-control @error('vehicle_type') is-invalid @enderror" 
                                value="{{ old('vehicle_type') }}" placeholder="e.g., Car, Bike, Scooter" required>
                            @error('vehicle_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <input type="text" name="brand" class="form-control" value="{{ old('brand') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Model</label>
                            <input type="text" name="model" class="form-control" value="{{ old('model') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <input type="text" name="color" class="form-control" value="{{ old('color') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Parking Type</label>
                            <select name="parking_type" class="form-control">
                                <option value="">Select Type</option>
                                <option value="covered" {{ old('parking_type') == 'covered' ? 'selected' : '' }}>Covered</option>
                                <option value="open" {{ old('parking_type') == 'open' ? 'selected' : '' }}>Open</option>
                                <option value="basement" {{ old('parking_type') == 'basement' ? 'selected' : '' }}>Basement</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Parking Slot Number</label>
                            <input type="text" name="parking_slot_number" class="form-control" value="{{ old('parking_slot_number') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <a href="{{ panel_route('vehicles.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Register Vehicle</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('unit_id').addEventListener('change', function() {
        const unitId = this.value;
        const residentSelect = document.getElementById('resident_id');
        residentSelect.innerHTML = '<option value="">Loading...</option>';
        
        if (unitId) {
            fetch(`/admin/units/${unitId}/residents`)
                .then(response => response.json())
                .then(data => {
                    residentSelect.innerHTML = '<option value="">Select Resident</option>';
                    data.forEach(resident => {
                        residentSelect.innerHTML += `<option value="${resident.id}">${resident.name}</option>`;
                    });
                })
                .catch(() => {
                    residentSelect.innerHTML = '<option value="">Error loading residents</option>';
                });
        } else {
            residentSelect.innerHTML = '<option value="">Select Resident</option>';
        }
    });
</script>
@endsection

