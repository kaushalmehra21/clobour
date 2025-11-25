@extends('layouts.admin.master')

@section('title', 'Resident Details')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Resident Details</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('residents.index') }}">Residents</a></li>
                    <li class="breadcrumb-item active">{{ $resident->name }}</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ panel_route('residents.edit', $resident) }}" class="btn btn-primary">
                    <i data-feather="edit" class="me-2"></i>Edit Resident
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Personal Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Name:</th>
                            <td>{{ $resident->name }}</td>
                        </tr>
                        <tr>
                            <th>Unit:</th>
                            <td><a href="{{ panel_route('units.show', $resident->unit) }}">{{ $resident->unit->unit_number }}</a></td>
                        </tr>
                        <tr>
                            <th>Type:</th>
                            <td><span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $resident->type)) }}</span></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge badge-{{ $resident->status == 'active' ? 'success' : ($resident->status == 'inactive' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $resident->status)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $resident->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $resident->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth:</th>
                            <td>{{ $resident->date_of_birth ? $resident->date_of_birth->format('M d, Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Move In Date:</th>
                            <td>{{ $resident->move_in_date ? $resident->move_in_date->format('M d, Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Move Out Date:</th>
                            <td>{{ $resident->move_out_date ? $resident->move_out_date->format('M d, Y') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Additional Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Aadhar Number:</th>
                            <td>{{ $resident->aadhar_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>PAN Number:</th>
                            <td>{{ $resident->pan_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $resident->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Emergency Contact:</th>
                            <td>
                                @if($resident->emergency_contact_name)
                                    {{ $resident->emergency_contact_name }}<br>
                                    <small>{{ $resident->emergency_contact_phone }}</small>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @if($resident->notes)
                        <tr>
                            <th>Notes:</th>
                            <td>{{ $resident->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Bills & Payments</h5>
                </div>
                <div class="card-body">
                    <p><strong>Total Bills:</strong> {{ $resident->bills->count() }}</p>
                    <p><strong>Pending Bills:</strong> {{ $resident->bills->where('status', 'pending')->count() }}</p>
                    <p><strong>Total Payments:</strong> {{ $resident->payments->where('status', 'completed')->sum('amount') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Complaints</h5>
                </div>
                <div class="card-body">
                    <p><strong>Total Complaints:</strong> {{ $resident->complaints->count() }}</p>
                    <p><strong>Open Complaints:</strong> {{ $resident->complaints->where('status', 'open')->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

