<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasColonyScope
{
    /**
     * Boot the trait
     */
    protected static function bootHasColonyScope()
    {
        static::addGlobalScope('colony', function (Builder $builder) {
            $user = auth()->user();
            
            if ($user && !$user->is_super_admin && $user->current_colony_id) {
                $builder->where('colony_id', $user->current_colony_id);
            }
        });
    }

    /**
     * Scope to get all records (including super admin)
     */
    public function scopeAllColonies(Builder $query)
    {
        return $query->withoutGlobalScope('colony');
    }

    /**
     * Scope to get records for a specific colony
     */
    public function scopeForColony(Builder $query, $colonyId)
    {
        return $query->withoutGlobalScope('colony')->where('colony_id', $colonyId);
    }
}

