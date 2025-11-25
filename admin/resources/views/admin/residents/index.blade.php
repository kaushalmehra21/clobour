@extends('layouts.admin.master')

@section('title', 'Residents Management')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Residents Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Residents</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ panel_route('residents.create') }}" class="btn btn-primary">
                    <i data-feather="user-plus" class="me-2"></i>Add Resident
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
            <form method="GET" action="{{ panel_route('residents.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search name, email, phone..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="unit_id" class="form-control">
                        <option value="">All Units</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->unit_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="owner" {{ request('type') == 'owner' ? 'selected' : '' }}>Owner</option>
                        <option value="tenant" {{ request('type') == 'tenant' ? 'selected' : '' }}>Tenant</option>
                        <option value="family_member" {{ request('type') == 'family_member' ? 'selected' : '' }}>Family Member</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="moved_out" {{ request('status') == 'moved_out' ? 'selected' : '' }}>Moved Out</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ panel_route('residents.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-borderless align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Unit</th>
                        <th>Type</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($residents as $resident)
                        <tr>
                            <td class="fw-semibold">{{ $resident->name }}</td>
                            <td>{{ $resident->unit->unit_number }}</td>
                            <td><span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $resident->type)) }}</span></td>
                            <td>{{ $resident->phone ?? '-' }}</td>
                            <td>{{ $resident->email ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $resident->status == 'active' ? 'success' : ($resident->status == 'inactive' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst(str_replace('_', ' ', $resident->status)) }}
                                </span>
                            </td>
                            <td class="text-end table-action-cell">
                                <div class="table-actions">
                                    <a href="{{ panel_route('residents.show', $resident) }}" class="btn btn-sm btn-outline-info"
                                        title="View {{ $resident->name }}">
                                        <i data-feather="eye"></i>
                                        <span>View</span>
                                    </a>
                                    <a href="{{ panel_route('residents.edit', $resident) }}" class="btn btn-sm btn-outline-secondary"
                                        title="Edit {{ $resident->name }}">
                                        <i data-feather="edit"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ panel_route('residents.destroy', $resident) }}" method="POST" class="mb-0"
                                        onsubmit="return confirm('Are you sure you want to delete this resident?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete {{ $resident->name }}">
                                            <i data-feather="trash-2"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No residents found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $residents->links() }}
        </div>
    </div>
</div>
@endsection

