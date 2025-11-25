@extends('layouts.admin.master')

@section('title', 'Operational Reports')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Operational Reports</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Operational</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ panel_route('reports.operational') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="month" name="month" class="form-control" value="{{ $month }}">
                </div>
                <div class="col-md-3">
                    <input type="number" name="year" class="form-control" value="{{ $year }}" placeholder="Year">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6>Total Complaints</h6>
                            <h3>{{ $complaints->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6>Total Visitors</h6>
                            <h3>{{ $visitorLogs->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6>Total Bookings</h6>
                            <h3>{{ $bookings->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <h5>Complaints Summary</h5>
            <div class="table-responsive mb-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Subject</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th>Priority</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($complaints as $complaint)
                            <tr>
                                <td>{{ $complaint->ticket_number }}</td>
                                <td>{{ $complaint->subject }}</td>
                                <td>{{ $complaint->unit->unit_number }}</td>
                                <td>{{ ucfirst($complaint->status) }}</td>
                                <td>{{ ucfirst($complaint->priority) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No complaints found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

