<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['resident_id', 'vehicle_number', 'vehicle_type', 'brand', 'model', 'status', 'parking_slot_number', 'colony_id'];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'colony_id',
        'resident_id',
        'unit_id',
        'vehicle_number',
        'vehicle_type',
        'brand',
        'model',
        'color',
        'parking_type',
        'parking_slot_number',
        'status',
        'notes',
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

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}
