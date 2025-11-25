<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'colony_id',
        'unit_number',
        'block',
        'floor',
        'type',
        'area',
        'status',
        'description',
    ];

    protected $casts = [
        'area' => 'decimal:2',
    ];

    // Relationships
    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    public function activeResident()
    {
        return $this->hasOne(Resident::class)->where('status', 'active');
    }

    public function bills()
    {
        return $this->hasMany(MonthlyBill::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}
