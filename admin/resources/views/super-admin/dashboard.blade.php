@extends('layouts.admin.master')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Super Admin Dashboard</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Overview</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Total Colonies</h6>
                    <h3>{{ $stats['total_colonies'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Active Colonies</h6>
                    <h3>{{ $stats['active_colonies'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6>Suspended</h6>
                    <h3>{{ $stats['suspended_colonies'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>Total Users</h6>
                    <h3>{{ $stats['total_users'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Colonies</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Status</th>
                                    <th>Plan</th>
                                    <th>Created</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentColonies as $colony)
                                    <tr>
                                        <td>{{ $colony->name }}</td>
                                        <td>{{ $colony->code }}</td>
                                        <td>
                                            <span class="badge badge-{{ $colony->status == 'active' ? 'success' : ($colony->status == 'suspended' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($colony->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $colony->plan?->name ?? '-' }}</td>
                                        <td>{{ $colony->created_at->format('M d, Y') }}</td>
                                        <td class="text-end table-action-cell">
                                            <div class="table-actions">
                                                <a href="{{ route('super-admin.colonies.show', $colony) }}" class="btn btn-sm btn-outline-info"
                                                    title="View {{ $colony->name }}">
                                                    <i data-feather="eye"></i>
                                                    <span>View</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No colonies found</td>
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

