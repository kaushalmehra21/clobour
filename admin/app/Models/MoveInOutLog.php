<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoveInOutLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'colony_id',
        'resident_id',
        'unit_id',
        'type',
        'date',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}
