@extends('layouts.admin.master')

@section('title', 'Unit Details')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Unit Details</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('units.index') }}">Units</a></li>
                    <li class="breadcrumb-item active">{{ $unit->unit_number }}</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ panel_route('units.edit', $unit) }}" class="btn btn-primary">
                    <i data-feather="edit" class="me-2"></i>Edit Unit
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Unit Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Unit Number:</th>
                            <td>{{ $unit->unit_number }}</td>
                        </tr>
                        <tr>
                            <th>Block:</th>
                            <td>{{ $unit->block ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Floor:</th>
                            <td>{{ $unit->floor ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Type:</th>
                            <td><span class="badge badge-secondary">{{ ucfirst($unit->type) }}</span></td>
                        </tr>
                        <tr>
                            <th>Area:</th>
                            <td>{{ $unit->area ? $unit->area . ' sqft' : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge badge-{{ $unit->status == 'occupied' ? 'success' : ($unit->status == 'vacant' ? 'warning' : 'info') }}">
                                    {{ ucfirst(str_replace('_', ' ', $unit->status)) }}
                                </span>
                            </td>
                        </tr>
                        @if($unit->description)
                        <tr>
                            <th>Description:</th>
                            <td>{{ $unit->description }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Current Resident</h5>
                </div>
                <div class="card-body">
                    @if($unit->activeResident)
                        <p><strong>Name:</strong> {{ $unit->activeResident->name }}</p>
                        <p><strong>Type:</strong> {{ ucfirst($unit->activeResident->type) }}</p>
                        <p><strong>Phone:</strong> {{ $unit->activeResident->phone ?? '-' }}</p>
                        <p><strong>Email:</strong> {{ $unit->activeResident->email ?? '-' }}</p>
                        <a href="{{ panel_route('residents.show', $unit->activeResident) }}" class="btn btn-sm btn-primary">View Details</a>
                    @else
                        <p class="text-muted">No active resident</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Residents History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Move In</th>
                                    <th>Move Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unit->residents as $resident)
                                    <tr>
                                        <td>{{ $resident->name }}</td>
                                        <td>{{ ucfirst($resident->type) }}</td>
                                        <td>{{ $resident->phone ?? '-' }}</td>
                                        <td><span class="badge badge-{{ $resident->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($resident->status) }}</span></td>
                                        <td>{{ $resident->move_in_date ? $resident->move_in_date->format('M d, Y') : '-' }}</td>
                                        <td>{{ $resident->move_out_date ? $resident->move_out_date->format('M d, Y') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No residents found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

