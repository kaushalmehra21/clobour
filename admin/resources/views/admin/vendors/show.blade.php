@extends('layouts.admin.master')

@section('title', 'Vendor Details')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Vendor Details</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('vendors.index') }}">Vendors</a></li>
                    <li class="breadcrumb-item active">{{ $vendor->name }}</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ panel_route('vendors.edit', $vendor) }}" class="btn btn-primary">
                    <i data-feather="edit" class="me-2"></i>Edit Vendor
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Vendor Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Name:</th>
                            <td>{{ $vendor->name }}</td>
                        </tr>
                        <tr>
                            <th>Contact Person:</th>
                            <td>{{ $vendor->contact_person ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $vendor->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $vendor->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alternate Phone:</th>
                            <td>{{ $vendor->alternate_phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>GST Number:</th>
                            <td>{{ $vendor->gst_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>PAN Number:</th>
                            <td>{{ $vendor->pan_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge badge-{{ $vendor->is_active ? 'success' : 'secondary' }}">
                                    {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        @if($vendor->address)
                        <tr>
                            <th>Address:</th>
                            <td>{{ $vendor->address }}</td>
                        </tr>
                        @endif
                        @if($vendor->notes)
                        <tr>
                            <th>Notes:</th>
                            <td>{{ $vendor->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Expenses</h5>
                </div>
                <div class="card-body">
                    <p><strong>Total Expenses:</strong> {{ $vendor->expenses->count() }}</p>
                    <p><strong>Total Amount:</strong> ₹{{ number_format($vendor->expenses->sum('amount'), 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

