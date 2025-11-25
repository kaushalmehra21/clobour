@extends('layouts.admin.master')

@section('title', 'Financial Reports')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Financial Reports</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('reports.index') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Financial</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ panel_route('reports.financial') }}" class="row g-3">
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
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6>Total Billed</h6>
                            <h3>₹{{ number_format($totalBilled, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6>Total Collected</h6>
                            <h3>₹{{ number_format($totalCollected, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6>Total Pending</h6>
                            <h3>₹{{ number_format($totalPending, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h6>Total Expenses</h6>
                            <h3>₹{{ number_format($totalExpenses, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Net Income: ₹{{ number_format($netIncome, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <h5>Payment Details</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Payment Number</th>
                            <th>Unit</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_number }}</td>
                                <td>{{ $payment->unit->unit_number }}</td>
                                <td>₹{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ ucfirst($payment->payment_method) }}</td>
                                <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No payments found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

