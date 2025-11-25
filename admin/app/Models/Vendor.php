<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'colony_id',
        'name',
        'contact_person',
        'email',
        'phone',
        'alternate_phone',
        'address',
        'gst_number',
        'pan_number',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}
