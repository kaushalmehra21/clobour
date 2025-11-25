@extends('layouts.admin.master')

@section('title', 'Vendors Management')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Vendors Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Vendors</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ panel_route('vendors.create') }}" class="btn btn-primary">
                    <i data-feather="plus" class="me-2"></i>Add Vendor
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
            <form method="GET" action="{{ panel_route('vendors.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search vendor..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="is_active" class="form-control">
                        <option value="">All Status</option>
                        <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-borderless align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $vendor)
                        <tr>
                            <td class="fw-semibold">{{ $vendor->name }}</td>
                            <td>{{ $vendor->contact_person ?? '-' }}</td>
                            <td>{{ $vendor->phone ?? '-' }}</td>
                            <td>{{ $vendor->email ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $vendor->is_active ? 'success' : 'secondary' }}">
                                    {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end table-action-cell">
                                <div class="table-actions">
                                    <a href="{{ panel_route('vendors.show', $vendor) }}" class="btn btn-sm btn-outline-info"
                                        title="View {{ $vendor->name }}">
                                        <i data-feather="eye"></i>
                                        <span>View</span>
                                    </a>
                                    <a href="{{ panel_route('vendors.edit', $vendor) }}" class="btn btn-sm btn-outline-secondary"
                                        title="Edit {{ $vendor->name }}">
                                        <i data-feather="edit"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ panel_route('vendors.destroy', $vendor) }}" method="POST" class="mb-0"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete {{ $vendor->name }}">
                                            <i data-feather="trash-2"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No vendors found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $vendors->links() }}
        </div>
    </div>
</div>
@endsection

