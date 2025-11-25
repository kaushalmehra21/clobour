<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'colony_id',
        'visitor_id',
        'unit_id',
        'visitor_name',
        'phone',
        'purpose',
        'vehicle_number',
        'entry_time',
        'exit_time',
        'entry_verified_by',
        'exit_verified_by',
        'notes',
    ];

    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
    ];

    // Relationships
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function entryVerifiedBy()
    {
        return $this->belongsTo(User::class, 'entry_verified_by');
    }

    public function exitVerifiedBy()
    {
        return $this->belongsTo(User::class, 'exit_verified_by');
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}
