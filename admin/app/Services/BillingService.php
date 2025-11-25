<?php

namespace App\Services;

use App\Models\Unit;
use App\Models\MonthlyBill;
use App\Models\Charge;
use App\Repositories\MonthlyBillRepository;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BillingService
{
    protected $billRepository;

    public function __construct(MonthlyBillRepository $billRepository)
    {
        $this->billRepository = $billRepository;
    }

    /**
     * Generate monthly bills for all units
     */
    public function generateMonthlyBills(string $month, $colonyId = null): array
    {
        $query = Unit::with('activeResident');
        if ($colonyId) {
            $query->where('colony_id', $colonyId);
        }
        $units = $query->get();
        
        $chargeQuery = Charge::where('is_active', true);
        if ($colonyId) {
            $chargeQuery->where('colony_id', $colonyId);
        }
        $charges = $chargeQuery->get();
        $generated = 0;
        $errors = [];

        foreach ($units as $unit) {
            try {
                // Check if bill already exists for this month
                $existingBill = MonthlyBill::where('unit_id', $unit->id)
                    ->where('month', $month)
                    ->first();

                if ($existingBill) {
                    continue;
                }

                $totalAmount = 0;
                $chargeDetails = [];

                foreach ($charges as $charge) {
                    $amount = $this->calculateChargeAmount($charge, $unit);
                    $totalAmount += $amount;
                    $chargeDetails[] = [
                        'charge_id' => $charge->id,
                        'charge_name' => $charge->name,
                        'amount' => $amount,
                    ];
                }

                $bill = MonthlyBill::create([
                    'colony_id' => $unit->colony_id,
                    'unit_id' => $unit->id,
                    'resident_id' => $unit->activeResident?->id,
                    'bill_number' => $this->generateBillNumber(),
                    'bill_date' => now(),
                    'due_date' => Carbon::parse($month . '-01')->addDays(10),
                    'month' => $month,
                    'total_amount' => $totalAmount,
                    'paid_amount' => 0,
                    'pending_amount' => $totalAmount,
                    'late_fee' => 0,
                    'status' => 'pending',
                    'charge_details' => $chargeDetails,
                ]);

                $generated++;
            } catch (\Exception $e) {
                $errors[] = "Unit {$unit->unit_number}: " . $e->getMessage();
            }
        }

        return [
            'generated' => $generated,
            'errors' => $errors,
        ];
    }

    /**
     * Calculate charge amount based on charge type
     */
    protected function calculateChargeAmount(Charge $charge, Unit $unit): float
    {
        return match ($charge->type) {
            'fixed' => $charge->amount,
            'per_sqft' => ($charge->per_sqft_rate ?? 0) * ($unit->area ?? 0),
            'per_unit' => $charge->amount,
            default => 0,
        };
    }

    /**
     * Generate unique bill number
     */
    protected function generateBillNumber(): string
    {
        return 'BILL-' . date('Ymd') . '-' . strtoupper(Str::random(6));
    }

    /**
     * Calculate late fee for overdue bills
     */
    public function calculateLateFee(MonthlyBill $bill, float $lateFeeRate = 0.02): float
    {
        if ($bill->status === 'paid' || $bill->due_date >= now()) {
            return 0;
        }

        $daysOverdue = now()->diffInDays($bill->due_date);
        return $bill->pending_amount * $lateFeeRate * $daysOverdue;
    }
}

