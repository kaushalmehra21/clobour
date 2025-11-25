<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'colony_id',
        'resident_id',
        'unit_id',
        'name',
        'phone',
        'email',
        'purpose',
        'number_of_visitors',
        'vehicle_number',
        'status',
        'otp',
        'qr_code',
        'expected_arrival',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'expected_arrival' => 'datetime',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function logs()
    {
        return $this->hasMany(VisitorLog::class);
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}
