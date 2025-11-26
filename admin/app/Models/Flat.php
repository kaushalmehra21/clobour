<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'block',
        'floor',
        'type',
        'colony_id',
        'status',
    ];

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}

