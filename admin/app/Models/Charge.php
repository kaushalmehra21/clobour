<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    use HasFactory;

    protected $fillable = [
        'colony_id',
        'name',
        'slug',
        'type',
        'amount',
        'per_sqft_rate',
        'is_active',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'per_sqft_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}
