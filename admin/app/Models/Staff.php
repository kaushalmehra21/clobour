<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'name',
        'type',
        'phone',
        'address',
        'identity_proof',
        'is_blocked',
        'colony_id',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function logs()
    {
        return $this->hasMany(StaffLog::class);
    }
}

