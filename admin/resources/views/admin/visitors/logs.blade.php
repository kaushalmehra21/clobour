@extends('layouts.admin.master')

@section('title', 'Visitor Logs')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Visitor Logs</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Visitor Logs</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ panel_route('visitors.index') }}" class="btn btn-secondary">
                    <i data-feather="arrow-left" class="me-2"></i>Back to Visitors
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
                        <th>Visitor Name</th>
                        <th>Phone</th>
                        <th>Unit</th>
                        <th>Purpose</th>
                        <th>Entry Time</th>
                        <th>Exit Time</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="fw-semibold">{{ $log->visitor_name }}</td>
                            <td>{{ $log->phone ?? '-' }}</td>
                            <td>{{ $log->unit->unit_number }}</td>
                            <td>{{ $log->purpose ?? '-' }}</td>
                            <td>{{ $log->entry_time->format('M d, Y h:i A') }}</td>
                            <td>
                                @if($log->exit_time)
                                    {{ $log->exit_time->format('M d, Y h:i A') }}
                                @else
                                    <span class="badge badge-warning">Still Inside</span>
                                @endif
                            </td>
                            <td class="text-end table-action-cell">
                                <div class="table-actions">
                                    @if(!$log->exit_time)
                                        <form action="{{ panel_route('visitor-logs.exit', $log) }}" method="POST" class="mb-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary" title="Mark exit for {{ $log->visitor_name }}">
                                                <i data-feather="log-out"></i>
                                                <span>Mark Exit</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection

