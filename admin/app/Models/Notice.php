<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'colony_id',
        'title',
        'content',
        'type',
        'priority',
        'publish_date',
        'expiry_date',
        'is_published',
        'send_notification',
        'target_audience',
        'attachments',
        'created_by',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'expiry_date' => 'date',
        'is_published' => 'boolean',
        'send_notification' => 'boolean',
        'target_audience' => 'array',
        'attachments' => 'array',
    ];

    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }
}
