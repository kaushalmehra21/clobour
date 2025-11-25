<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'colony_id',
        'resident_id',
        'unit_id',
        'category_id',
        'ticket_number',
        'subject',
        'description',
        'priority',
        'status',
        'assigned_to',
        'resolved_at',
        'resolution_notes',
        'attachments',
    ];

    protected $casts = [
        'resolved_at' => 'date',
        'attachments' => 'array',
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

    public function category()
    {
        return $this->belongsTo(ComplaintCategory::class, 'category_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(ComplaintComment::class);
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}
