@extends('layouts.admin.master')

@section('title', 'Payment Details')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Payment Details</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('payments.index') }}">Payments</a></li>
                    <li class="breadcrumb-item active">{{ $payment->payment_number }}</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Payment Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Payment Number:</th>
                            <td>{{ $payment->payment_number }}</td>
                        </tr>
                        <tr>
                            <th>Unit:</th>
                            <td>{{ $payment->unit->unit_number }}</td>
                        </tr>
                        <tr>
                            <th>Bill:</th>
                            <td>{{ $payment->bill?->bill_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Amount:</th>
                            <td class="fw-bold">₹{{ number_format($payment->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Payment Method:</th>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                        </tr>
                        <tr>
                            <th>Payment Date:</th>
                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge badge-{{ $payment->status == 'completed' ? 'success' : ($payment->status == 'failed' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                        </tr>
                        @if($payment->transaction_id)
                        <tr>
                            <th>Transaction ID:</th>
                            <td>{{ $payment->transaction_id }}</td>
                        </tr>
                        @endif
                        @if($payment->cheque_number)
                        <tr>
                            <th>Cheque Number:</th>
                            <td>{{ $payment->cheque_number }}</td>
                        </tr>
                        @endif
                        @if($payment->notes)
                        <tr>
                            <th>Notes:</th>
                            <td>{{ $payment->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

