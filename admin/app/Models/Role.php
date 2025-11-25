<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_default',
        'scope',
        'colony_id',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }

    public function givePermissionTo($permission): void
    {
        $permissionId = $permission instanceof Permission ? $permission->id : $permission;
        $this->permissions()->syncWithoutDetaching([$permissionId]);
    }

    public function scopeGlobal($query)
    {
        return $query->where('scope', 'global');
    }

    public function scopeColony($query, $colonyId = null)
    {
        $query = $query->where('scope', 'colony');
        
        if ($colonyId) {
            $query->where('colony_id', $colonyId);
        }
        
        return $query;
    }
}
