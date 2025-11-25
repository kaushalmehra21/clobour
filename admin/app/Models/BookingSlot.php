<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'colony_id',
        'amenity_id',
        'slot_date',
        'start_time',
        'end_time',
        'is_available',
        'price',
        'notes',
    ];

    protected $casts = [
        'slot_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    // Relationships
    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'slot_id');
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}
