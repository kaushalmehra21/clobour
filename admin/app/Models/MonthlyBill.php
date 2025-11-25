<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonthlyBill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'colony_id',
        'unit_id',
        'resident_id',
        'bill_number',
        'bill_date',
        'due_date',
        'month',
        'total_amount',
        'paid_amount',
        'pending_amount',
        'late_fee',
        'status',
        'charge_details',
        'notes',
    ];

    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'pending_amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'charge_details' => 'array',
    ];

    // Relationships
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
            ->orWhere(function ($q) {
                $q->where('status', 'pending')
                  ->where('due_date', '<', now());
            });
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}
