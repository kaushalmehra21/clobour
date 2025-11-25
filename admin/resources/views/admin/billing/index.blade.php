@extends('layouts.admin.master')

@section('title', 'Billing Management')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Billing Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Billing</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#generateModal">
                    <i data-feather="plus" class="me-2"></i>Generate Bills
                </button>
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
            <form method="GET" action="{{ panel_route('billing.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="month" name="month" class="form-control" value="{{ request('month', now()->format('Y-m')) }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
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
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-borderless align-middle">
                <thead>
                    <tr>
                        <th>Bill Number</th>
                        <th>Unit</th>
                        <th>Resident</th>
                        <th>Month</th>
                        <th>Total Amount</th>
                        <th>Paid</th>
                        <th>Pending</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bills as $bill)
                        <tr>
                            <td class="fw-semibold">{{ $bill->bill_number }}</td>
                            <td>{{ $bill->unit->unit_number }}</td>
                            <td>{{ $bill->resident?->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($bill->month . '-01')->format('M Y') }}</td>
                            <td>₹{{ number_format($bill->total_amount, 2) }}</td>
                            <td>₹{{ number_format($bill->paid_amount, 2) }}</td>
                            <td>₹{{ number_format($bill->pending_amount, 2) }}</td>
                            <td>
                                <span class="badge badge-{{ $bill->status == 'paid' ? 'success' : ($bill->status == 'overdue' ? 'danger' : ($bill->status == 'partial' ? 'warning' : 'secondary')) }}">
                                    {{ ucfirst($bill->status) }}
                                </span>
                            </td>
                            <td class="text-end table-action-cell">
                                <div class="table-actions">
                                    <a href="{{ panel_route('billing.show', $bill) }}" class="btn btn-sm btn-outline-info"
                                        title="View bill {{ $bill->bill_number }}">
                                        <i data-feather="eye"></i>
                                        <span>View</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No bills found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $bills->links() }}
        </div>
    </div>
</div>

<!-- Generate Bills Modal -->
<div class="modal fade" id="generateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ panel_route('billing.generate') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Generate Monthly Bills</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Month <span class="text-danger">*</span></label>
                        <input type="month" name="month" class="form-control" value="{{ now()->format('Y-m') }}" required>
                    </div>
                    <p class="text-muted">This will generate bills for all active units for the selected month.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate Bills</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

