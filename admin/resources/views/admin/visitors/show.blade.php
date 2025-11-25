@extends('layouts.admin.master')

@section('title', 'Visitor Details')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Visitor Details</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('visitors.index') }}">Visitors</a></li>
                    <li class="breadcrumb-item active">{{ $visitor->name }}</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Visitor Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Name:</th>
                            <td>{{ $visitor->name }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $visitor->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $visitor->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Unit:</th>
                            <td>{{ $visitor->unit->unit_number }}</td>
                        </tr>
                        <tr>
                            <th>Resident:</th>
                            <td>{{ $visitor->resident?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Purpose:</th>
                            <td>{{ $visitor->purpose ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Number of Visitors:</th>
                            <td>{{ $visitor->number_of_visitors ?? 1 }}</td>
                        </tr>
                        <tr>
                            <th>Vehicle Number:</th>
                            <td>{{ $visitor->vehicle_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Expected Arrival:</th>
                            <td>{{ $visitor->expected_arrival?->format('M d, Y h:i A') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge badge-{{ $visitor->status == 'approved' ? 'success' : ($visitor->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($visitor->status) }}
                                </span>
                            </td>
                        </tr>
                        @if($visitor->otp)
                        <tr>
                            <th>OTP:</th>
                            <td><strong>{{ $visitor->otp }}</strong></td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Visitor Logs</h5>
                </div>
                <div class="card-body">
                    @forelse($visitor->logs as $log)
                        <div class="mb-3 p-3 border rounded">
                            <p><strong>Entry:</strong> {{ $log->entry_time->format('M d, Y h:i A') }}</p>
                            @if($log->exit_time)
                                <p><strong>Exit:</strong> {{ $log->exit_time->format('M d, Y h:i A') }}</p>
                            @else
                                <form action="{{ panel_route('visitor-logs.exit', $log) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">Mark Exit</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted">No logs found</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

