@extends('layouts.admin.master')

@section('title', 'Amenities Management')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Amenities Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Amenities</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ panel_route('amenities.create') }}" class="btn btn-primary">
                    <i data-feather="plus" class="me-2"></i>Add Amenity
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
        <div class="card-body table-responsive">
            <table class="table table-borderless align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Booking Fee</th>
                        <th>Requires Approval</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($amenities as $amenity)
                        <tr>
                            <td class="fw-semibold">{{ $amenity->name }}</td>
                            <td>₹{{ number_format($amenity->booking_fee, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $amenity->requires_approval ? 'warning' : 'success' }}">
                                    {{ $amenity->requires_approval ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $amenity->is_active ? 'success' : 'secondary' }}">
                                    {{ $amenity->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end table-action-cell">
                                <div class="table-actions">
                                    <a href="{{ panel_route('amenities.show', $amenity) }}" class="btn btn-sm btn-outline-info"
                                        title="View {{ $amenity->name }}">
                                        <i data-feather="eye"></i>
                                        <span>View</span>
                                    </a>
                                    <a href="{{ panel_route('amenities.edit', $amenity) }}" class="btn btn-sm btn-outline-secondary"
                                        title="Edit {{ $amenity->name }}">
                                        <i data-feather="edit"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ panel_route('amenities.destroy', $amenity) }}" method="POST" class="mb-0"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete {{ $amenity->name }}">
                                            <i data-feather="trash-2"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No amenities found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $amenities->links() }}
        </div>
    </div>
</div>
@endsection

