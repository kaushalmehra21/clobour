@extends('layouts.admin.master')

@section('title', 'Colonies Management')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Colonies Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Colonies</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ route('super-admin.colonies.create') }}" class="btn btn-primary">
                    <i data-feather="plus" class="me-2"></i>Create Colony
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
                        <th>Code</th>
                        <th>City</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Expires At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($colonies as $colony)
                        <tr>
                            <td class="fw-semibold">{{ $colony->name }}</td>
                            <td>{{ $colony->code }}</td>
                            <td>{{ $colony->city ?? '-' }}</td>
                            <td>{{ $colony->plan?->name ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $colony->status == 'active' ? 'success' : ($colony->status == 'suspended' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($colony->status) }}
                                </span>
                            </td>
                            <td>{{ $colony->expires_at?->format('M d, Y') ?? '-' }}</td>
                            <td class="text-end table-action-cell">
                                <div class="table-actions">
                                    <a href="{{ route('super-admin.colonies.show', $colony) }}" class="btn btn-sm btn-outline-info"
                                        title="View {{ $colony->name }}">
                                        <i data-feather="eye"></i>
                                        <span>View</span>
                                    </a>
                                    <a href="{{ route('super-admin.colonies.edit', $colony) }}" class="btn btn-sm btn-outline-secondary"
                                        title="Edit {{ $colony->name }}">
                                        <i data-feather="edit"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ route('super-admin.colonies.impersonate', $colony) }}" method="POST" class="mb-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary" title="Impersonate {{ $colony->name }}">
                                            <i data-feather="user"></i>
                                            <span>Impersonate</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('super-admin.colonies.destroy', $colony) }}" method="POST" class="mb-0"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete {{ $colony->name }}">
                                            <i data-feather="trash-2"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No colonies found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $colonies->links() }}
        </div>
    </div>
</div>
@endsection

