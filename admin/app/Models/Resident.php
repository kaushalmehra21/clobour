<?php

namespace App\Models;

use App\Models\FamilyMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Resident extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'colony_id',
        'flat_id',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }

    public function flat()
    {
        return $this->belongsTo(Flat::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

    public function familyMembers()
    {
        return $this->hasMany(FamilyMember::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'colony_id',
        'unit_id',
        'name',
        'email',
        'phone',
        'alternate_phone',
        'type',
        'status',
        'date_of_birth',
        'aadhar_number',
        'pan_number',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'move_in_date',
        'move_out_date',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'move_in_date' => 'date',
        'move_out_date' => 'date',
    ];

    // Relationships
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function bills()
    {
        return $this->hasMany(MonthlyBill::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function moveInOutLogs()
    {
        return $this->hasMany(MoveInOutLog::class);
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}
