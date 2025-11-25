@extends('layouts.admin.master')

@section('title', 'Record Payment')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>Record Payment</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ panel_route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ panel_route('payments.index') }}">Payments</a></li>
                    <li class="breadcrumb-item active">Record</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ panel_route('payments.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Unit <span class="text-danger">*</span></label>
                            <select name="unit_id" id="unit_id" class="form-control @error('unit_id') is-invalid @enderror" required>
                                <option value="">Select Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->unit_number }}</option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Bill (Optional)</label>
                            <select name="bill_id" id="bill_id" class="form-control">
                                <option value="">Select Bill</option>
                                @foreach($bills as $bill)
                                    <option value="{{ $bill->id }}" {{ old('bill_id', $billId) == $bill->id ? 'selected' : '' }}>
                                        {{ $bill->bill_number }} - ₹{{ number_format($bill->pending_amount, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" 
                                value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                            <select name="payment_method" id="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="upi" {{ old('payment_method') == 'upi' ? 'selected' : '' }}>UPI</option>
                                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" 
                                value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6" id="cheque_fields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Cheque Number</label>
                            <input type="text" name="cheque_number" class="form-control" value="{{ old('cheque_number') }}">
                        </div>
                    </div>
                    <div class="col-md-6" id="cheque_date_field" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Cheque Date</label>
                            <input type="date" name="cheque_date" class="form-control" value="{{ old('cheque_date') }}">
                        </div>
                    </div>
                    <div class="col-md-6" id="bank_name_field" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name') }}">
                        </div>
                    </div>
                    <div class="col-md-6" id="transaction_id_field" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Transaction ID</label>
                            <input type="text" name="transaction_id" class="form-control" value="{{ old('transaction_id') }}">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <a href="{{ panel_route('payments.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Record Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.getElementById('payment_method').addEventListener('change', function() {
        const method = this.value;
        const chequeFields = document.getElementById('cheque_fields');
        const chequeDateField = document.getElementById('cheque_date_field');
        const bankNameField = document.getElementById('bank_name_field');
        const transactionIdField = document.getElementById('transaction_id_field');
        
        if (method === 'cheque') {
            chequeFields.style.display = 'block';
            chequeDateField.style.display = 'block';
            bankNameField.style.display = 'block';
            transactionIdField.style.display = 'none';
        } else if (['online', 'upi', 'card', 'bank_transfer'].includes(method)) {
            chequeFields.style.display = 'none';
            chequeDateField.style.display = 'none';
            bankNameField.style.display = 'none';
            transactionIdField.style.display = 'block';
        } else {
            chequeFields.style.display = 'none';
            chequeDateField.style.display = 'none';
            bankNameField.style.display = 'none';
            transactionIdField.style.display = 'none';
        }
    });
</script>
@endsection

