@extends('layouts.admin.master')

@section('title', 'Visitors Management')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Visitors Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Visitors</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ panel_route('visitors.logs') }}" class="btn btn-secondary me-2">
                    <i data-feather="list" class="me-2"></i>View Logs
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
            <form method="GET" action="{{ panel_route('visitors.index') }}" class="row g-3">
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
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
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
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
                        <th>Phone</th>
                        <th>Unit</th>
                        <th>Purpose</th>
                        <th>Expected Arrival</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visitors as $visitor)
                        <tr>
                            <td class="fw-semibold">{{ $visitor->name }}</td>
                            <td>{{ $visitor->phone ?? '-' }}</td>
                            <td>{{ $visitor->unit->unit_number }}</td>
                            <td>{{ $visitor->purpose ?? '-' }}</td>
                            <td>{{ $visitor->expected_arrival?->format('M d, Y h:i A') ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $visitor->status == 'approved' ? 'success' : ($visitor->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($visitor->status) }}
                                </span>
                            </td>
                            <td class="text-end table-action-cell">
                                <div class="table-actions">
                                    <a href="{{ panel_route('visitors.show', $visitor) }}" class="btn btn-sm btn-outline-info"
                                        title="View {{ $visitor->name }}">
                                        <i data-feather="eye"></i>
                                        <span>View</span>
                                    </a>
                                    @if($visitor->status == 'pending')
                                        <form action="{{ panel_route('visitors.approve', $visitor) }}" method="POST" class="mb-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Approve {{ $visitor->name }}">
                                                <i data-feather="check"></i>
                                                <span>Approve</span>
                                            </button>
                                        </form>
                                        <form action="{{ panel_route('visitors.reject', $visitor) }}" method="POST" class="mb-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject {{ $visitor->name }}">
                                                <i data-feather="x"></i>
                                                <span>Reject</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No visitors found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $visitors->links() }}
        </div>
    </div>
</div>
@endsection

