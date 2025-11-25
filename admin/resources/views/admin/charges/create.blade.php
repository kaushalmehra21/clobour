@extends('layouts.admin.master')

@section('title', 'Add Charge')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Add Charge</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('charges.index') }}">Charges</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ panel_route('charges.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" id="charge_type" class="form-control @error('type') is-invalid @enderror" required>
                                <option value="">Select Type</option>
                                <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                <option value="per_sqft" {{ old('type') == 'per_sqft' ? 'selected' : '' }}>Per Square Feet</option>
                                <option value="per_unit" {{ old('type') == 'per_unit' ? 'selected' : '' }}>Per Unit</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6" id="amount_field">
                        <div class="mb-3">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                                value="{{ old('amount') }}">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6" id="per_sqft_field" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Rate per sqft <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="per_sqft_rate" class="form-control @error('per_sqft_rate') is-invalid @enderror" 
                                value="{{ old('per_sqft_rate') }}">
                            @error('per_sqft_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <a href="{{ panel_route('charges.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Charge</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('charge_type').addEventListener('change', function() {
        const type = this.value;
        const amountField = document.getElementById('amount_field');
        const perSqftField = document.getElementById('per_sqft_field');
        
        if (type === 'per_sqft') {
            amountField.style.display = 'none';
            perSqftField.style.display = 'block';
        } else {
            amountField.style.display = 'block';
            perSqftField.style.display = 'none';
        }
    });
</script>
@endsection

