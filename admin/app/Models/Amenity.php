<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'colony_id',
        'name',
        'slug',
        'description',
        'booking_fee',
        'max_advance_booking_days',
        'min_advance_booking_hours',
        'requires_approval',
        'is_active',
        'available_days',
        'opening_time',
        'closing_time',
        'max_booking_duration_hours',
        'terms_and_conditions',
    ];

    protected $casts = [
        'booking_fee' => 'decimal:2',
        'available_days' => 'array',
        'opening_time' => 'datetime',
        'closing_time' => 'datetime',
        'requires_approval' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function slots()
    {
        return $this->hasMany(BookingSlot::class);
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
