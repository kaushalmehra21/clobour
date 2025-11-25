@extends('layouts.admin.master')

@section('title', 'Expense Details')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Expense Details</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('expenses.index') }}">Expenses</a></li>
                    <li class="breadcrumb-item active">{{ $expense->title }}</li>
                </ol>
            </div>
            <div class="col-lg-6 text-end">
                <a href="{{ panel_route('expenses.edit', $expense) }}" class="btn btn-primary">
                    <i data-feather="edit" class="me-2"></i>Edit Expense
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Expense Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Title:</th>
                            <td>{{ $expense->title }}</td>
                        </tr>
                        <tr>
                            <th>Category:</th>
                            <td>{{ $expense->category?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Vendor:</th>
                            <td>{{ $expense->vendor?->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Amount:</th>
                            <td class="fw-bold">₹{{ number_format($expense->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Expense Date:</th>
                            <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Payment Method:</th>
                            <td>{{ ucfirst($expense->payment_method) }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge badge-{{ $expense->status == 'approved' ? 'success' : ($expense->status == 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($expense->status) }}
                                </span>
                            </td>
                        </tr>
                        @if($expense->receipt_number)
                        <tr>
                            <th>Receipt Number:</th>
                            <td>{{ $expense->receipt_number }}</td>
                        </tr>
                        @endif
                        @if($expense->description)
                        <tr>
                            <th>Description:</th>
                            <td>{{ $expense->description }}</td>
                        </tr>
                        @endif
                        @if($expense->notes)
                        <tr>
                            <th>Notes:</th>
                            <td>{{ $expense->notes }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Additional Information</h5>
                </div>
                <div class="card-body">
                    @if($expense->receipt_file)
                        <p><strong>Receipt:</strong> <a href="{{ Storage::url($expense->receipt_file) }}" target="_blank" class="btn btn-sm btn-primary">View Receipt</a></p>
                    @endif
                    <p><strong>Created By:</strong> {{ $expense->createdBy->name }}</p>
                    <p><strong>Created At:</strong> {{ $expense->created_at->format('M d, Y h:i A') }}</p>
                    @if($expense->approvedBy)
                        <p><strong>Approved By:</strong> {{ $expense->approvedBy->name }}</p>
                        <p><strong>Approved At:</strong> {{ $expense->approved_at?->format('M d, Y') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

