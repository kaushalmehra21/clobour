@extends('layouts.admin.master')

@section('title', 'Bill Details')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Bill Details</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('billing.index') }}">Billing</a></li>
                    <li class="breadcrumb-item active">{{ $billing->bill_number }}</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Bill Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Bill Number:</th>
                            <td>{{ $billing->bill_number }}</td>
                        </tr>
                        <tr>
                            <th>Unit:</th>
                            <td>{{ $billing->unit->unit_number }}</td>
                        </tr>
                        <tr>
                            <th>Resident:</th>
                            <td>{{ $billing->resident?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Month:</th>
                            <td>{{ \Carbon\Carbon::parse($billing->month . '-01')->format('F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Bill Date:</th>
                            <td>{{ $billing->bill_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Due Date:</th>
                            <td>{{ $billing->due_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge badge-{{ $billing->status == 'paid' ? 'success' : ($billing->status == 'overdue' ? 'danger' : ($billing->status == 'partial' ? 'warning' : 'secondary')) }}">
                                    {{ ucfirst($billing->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Amount Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Total Amount:</th>
                            <td class="fw-bold">₹{{ number_format($billing->total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Paid Amount:</th>
                            <td class="text-success">₹{{ number_format($billing->paid_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Pending Amount:</th>
                            <td class="text-danger">₹{{ number_format($billing->pending_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Late Fee:</th>
                            <td>₹{{ number_format($billing->late_fee, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if($billing->charge_details)
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Charge Breakdown</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Charge Name</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($billing->charge_details as $charge)
                                <tr>
                                    <td>{{ $charge['charge_name'] }}</td>
                                    <td class="text-end">₹{{ number_format($charge['amount'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Payment History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Payment Number</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($billing->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->payment_number }}</td>
                                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                        <td>₹{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ ucfirst($payment->payment_method) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $payment->status == 'completed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No payments found</td>
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

