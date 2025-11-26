<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'name',
        'relation',
        'phone',
        'email',
        'dob',
        'gender',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}

