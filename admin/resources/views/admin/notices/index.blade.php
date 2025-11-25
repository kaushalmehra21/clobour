@extends('layouts.admin.master')

@section('title', 'Notices Management')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Notices Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Notices</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ panel_route('notices.create') }}" class="btn btn-primary">
                    <i data-feather="plus" class="me-2"></i>Create Notice
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
            <form method="GET" action="{{ panel_route('notices.index') }}" class="row g-3">
                <div class="col-md-2">
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                        <option value="maintenance" {{ request('type') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="meeting" {{ request('type') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                        <option value="emergency" {{ request('type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="is_published" class="form-control">
                        <option value="">All</option>
                        <option value="1" {{ request('is_published') == '1' ? 'selected' : '' }}>Published</option>
                        <option value="0" {{ request('is_published') == '0' ? 'selected' : '' }}>Draft</option>
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
                        <th>Title</th>
                        <th>Type</th>
                        <th>Priority</th>
                        <th>Publish Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notices as $notice)
                        <tr>
                            <td class="fw-semibold">{{ $notice->title }}</td>
                            <td><span class="badge badge-info">{{ ucfirst($notice->type) }}</span></td>
                            <td>
                                <span class="badge badge-{{ $notice->priority == 'high' ? 'danger' : ($notice->priority == 'medium' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($notice->priority) }}
                                </span>
                            </td>
                            <td>{{ $notice->publish_date->format('M d, Y') }}</td>
                            <td>
                                <span class="badge badge-{{ $notice->is_published ? 'success' : 'secondary' }}">
                                    {{ $notice->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="text-end table-action-cell">
                                <div class="table-actions">
                                    <a href="{{ panel_route('notices.show', $notice) }}" class="btn btn-sm btn-outline-info"
                                        title="View {{ $notice->title }}">
                                        <i data-feather="eye"></i>
                                        <span>View</span>
                                    </a>
                                    <a href="{{ panel_route('notices.edit', $notice) }}" class="btn btn-sm btn-outline-secondary"
                                        title="Edit {{ $notice->title }}">
                                        <i data-feather="edit"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ panel_route('notices.destroy', $notice) }}" method="POST" class="mb-0"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete {{ $notice->title }}">
                                            <i data-feather="trash-2"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No notices found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $notices->links() }}
        </div>
    </div>
</div>
@endsection

