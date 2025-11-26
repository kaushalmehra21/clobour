<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffLog extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id', 'entry_time', 'exit_time', 'note'];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}

