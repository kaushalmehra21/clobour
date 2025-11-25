<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\MonthlyBill;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Record payment for a bill
     */
    public function recordPayment(array $data): Payment
    {
        // Get colony_id from unit if not provided
        $colonyId = $data['colony_id'] ?? null;
        if (!$colonyId && isset($data['unit_id'])) {
            $unit = \App\Models\Unit::find($data['unit_id']);
            $colonyId = $unit?->colony_id;
        }
        
        $payment = Payment::create([
            'colony_id' => $colonyId,
            'bill_id' => $data['bill_id'] ?? null,
            'resident_id' => $data['resident_id'] ?? null,
            'unit_id' => $data['unit_id'],
            'payment_number' => $this->generatePaymentNumber(),
            'amount' => $data['amount'],
            'payment_method' => $data['payment_method'],
            'status' => $data['status'] ?? 'pending',
            'transaction_id' => $data['transaction_id'] ?? null,
            'payment_gateway' => $data['payment_gateway'] ?? null,
            'gateway_response' => $data['gateway_response'] ?? null,
            'payment_date' => $data['payment_date'] ?? now(),
            'cheque_number' => $data['cheque_number'] ?? null,
            'cheque_date' => $data['cheque_date'] ?? null,
            'bank_name' => $data['bank_name'] ?? null,
            'notes' => $data['notes'] ?? null,
            'received_by' => $data['received_by'] ?? auth()->id(),
        ]);

        // Update bill status if payment is for a bill
        if ($payment->bill_id) {
            $this->updateBillStatus($payment->bill);
        }

        return $payment;
    }

    /**
     * Update bill status based on payments
     */
    protected function updateBillStatus(MonthlyBill $bill): void
    {
        $totalPaid = $bill->payments()
            ->where('status', 'completed')
            ->sum('amount');

        $bill->paid_amount = $totalPaid;
        $bill->pending_amount = $bill->total_amount - $totalPaid + $bill->late_fee;

        if ($bill->pending_amount <= 0) {
            $bill->status = 'paid';
        } elseif ($totalPaid > 0) {
            $bill->status = 'partial';
        } elseif ($bill->due_date < now()) {
            $bill->status = 'overdue';
        }

        $bill->save();
    }

    /**
     * Generate unique payment number
     */
    protected function generatePaymentNumber(): string
    {
        return 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    }
}

