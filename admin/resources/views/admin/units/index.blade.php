@extends('layouts.admin.master')

@section('title', 'Units Management')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Units Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Units</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ panel_route('units.create') }}" class="btn btn-primary">
                    <i data-feather="plus" class="me-2"></i>Add Unit
                </a>
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
            <form method="GET" action="{{ panel_route('units.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search unit number..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="block" class="form-control">
                        <option value="">All Blocks</option>
                        @foreach(['A', 'B', 'C', 'D', 'E'] as $block)
                            <option value="{{ $block }}" {{ request('block') == $block ? 'selected' : '' }}>Block {{ $block }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                        <option value="vacant" {{ request('status') == 'vacant' ? 'selected' : '' }}>Vacant</option>
                        <option value="under_construction" {{ request('status') == 'under_construction' ? 'selected' : '' }}>Under Construction</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ panel_route('units.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-borderless align-middle">
                <thead>
                    <tr>
                        <th>Unit Number</th>
                        <th>Block</th>
                        <th>Floor</th>
                        <th>Type</th>
                        <th>Area (sqft)</th>
                        <th>Status</th>
                        <th>Resident</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                        <tr>
                            <td class="fw-semibold">{{ $unit->unit_number }}</td>
                            <td>{{ $unit->block ?? '-' }}</td>
                            <td>{{ $unit->floor ?? '-' }}</td>
                            <td><span class="badge badge-secondary">{{ ucfirst($unit->type) }}</span></td>
                            <td>{{ $unit->area ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $unit->status == 'occupied' ? 'success' : ($unit->status == 'vacant' ? 'warning' : 'info') }}">
                                    {{ ucfirst(str_replace('_', ' ', $unit->status)) }}
                                </span>
                            </td>
                            <td>{{ $unit->activeResident?->name ?? '-' }}</td>
                            <td class="text-end table-action-cell">
                                <div class="table-actions">
                                    <a href="{{ panel_route('units.show', $unit) }}" class="btn btn-sm btn-outline-info"
                                        title="View {{ $unit->unit_number }}">
                                        <i data-feather="eye"></i>
                                        <span>View</span>
                                    </a>
                                    <a href="{{ panel_route('units.edit', $unit) }}" class="btn btn-sm btn-outline-secondary"
                                        title="Edit {{ $unit->unit_number }}">
                                        <i data-feather="edit"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ panel_route('units.destroy', $unit) }}" method="POST" class="mb-0"
                                        onsubmit="return confirm('Are you sure you want to delete this unit?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete {{ $unit->unit_number }}">
                                            <i data-feather="trash-2"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No units found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $units->links() }}
        </div>
    </div>
</div>
@endsection

