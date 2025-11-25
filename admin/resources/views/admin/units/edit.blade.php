@extends('layouts.admin.master')

@section('title', 'Edit Unit')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Edit Unit</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('units.index') }}">Units</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ panel_route('units.update', $unit) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Unit Number <span class="text-danger">*</span></label>
                            <input type="text" name="unit_number" class="form-control @error('unit_number') is-invalid @enderror" 
                                value="{{ old('unit_number', $unit->unit_number) }}" required>
                            @error('unit_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Block</label>
                            <input type="text" name="block" class="form-control" value="{{ old('block', $unit->block) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Floor</label>
                            <input type="text" name="floor" class="form-control" value="{{ old('floor', $unit->floor) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                                <option value="flat" {{ old('type', $unit->type) == 'flat' ? 'selected' : '' }}>Flat</option>
                                <option value="shop" {{ old('type', $unit->type) == 'shop' ? 'selected' : '' }}>Shop</option>
                                <option value="office" {{ old('type', $unit->type) == 'office' ? 'selected' : '' }}>Office</option>
                                <option value="parking" {{ old('type', $unit->type) == 'parking' ? 'selected' : '' }}>Parking</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Area (sqft)</label>
                            <input type="number" step="0.01" name="area" class="form-control" value="{{ old('area', $unit->area) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="vacant" {{ old('status', $unit->status) == 'vacant' ? 'selected' : '' }}>Vacant</option>
                                <option value="occupied" {{ old('status', $unit->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                <option value="under_construction" {{ old('status', $unit->status) == 'under_construction' ? 'selected' : '' }}>Under Construction</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $unit->description) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <a href="{{ panel_route('units.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Unit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

