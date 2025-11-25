<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colony extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'state',
        'pincode',
        'phone',
        'email',
        'logo',
        'plan_id',
        'status',
        'expires_at',
        'max_units',
        'max_residents',
        'settings',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'settings' => 'array',
    ];

    // Relationships
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_colonies')
            ->withPivot('role_id', 'is_primary')
            ->withTimestamps();
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function residents()
    {
        return $this->hasMany(Resident::class);
    }

    public function bills()
    {
        return $this->hasMany(MonthlyBill::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function notices()
    {
        return $this->hasMany(Notice::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }
}
