<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = ['resident_id', 'colony_id', 'bill_number', 'amount', 'status', 'month', 'due_date'];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}

