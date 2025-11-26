<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['amenity_id', 'resident_id', 'date', 'slot', 'status', 'purpose'];

    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'colony_id',
        'amenity_id',
        'resident_id',
        'unit_id',
        'slot_id',
        'booking_number',
        'booking_date',
        'start_time',
        'end_time',
        'amount',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'special_requests',
        'number_of_guests',
        'cancellation_reason',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function slot()
    {
        return $this->belongsTo(BookingSlot::class, 'slot_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}
