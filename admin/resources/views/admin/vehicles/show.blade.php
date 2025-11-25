@extends('layouts.admin.master')

@section('title', 'Vehicle Details')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Vehicle Details</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('vehicles.index') }}">Vehicles</a></li>
                    <li class="breadcrumb-item active">{{ $vehicle->vehicle_number }}</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ panel_route('vehicles.edit', $vehicle) }}" class="btn btn-primary">
                    <i data-feather="edit" class="me-2"></i>Edit Vehicle
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Vehicle Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Vehicle Number:</th>
                            <td>{{ $vehicle->vehicle_number }}</td>
                        </tr>
                        <tr>
                            <th>Vehicle Type:</th>
                            <td>{{ $vehicle->vehicle_type }}</td>
                        </tr>
                        <tr>
                            <th>Brand:</th>
                            <td>{{ $vehicle->brand ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Model:</th>
                            <td>{{ $vehicle->model ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Color:</th>
                            <td>{{ $vehicle->color ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Unit:</th>
                            <td>{{ $vehicle->unit->unit_number }}</td>
                        </tr>
                        <tr>
                            <th>Resident:</th>
                            <td>{{ $vehicle->resident?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Parking Type:</th>
                            <td>{{ $vehicle->parking_type ? ucfirst($vehicle->parking_type) : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Parking Slot:</th>
                            <td>{{ $vehicle->parking_slot_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge badge-{{ $vehicle->status == 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($vehicle->status) }}
                                </span>
                            </td>
                        </tr>
                        @if($vehicle->notes)
                        <tr>
                            <th>Notes:</th>
                            <td>{{ $vehicle->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

