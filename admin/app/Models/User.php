<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Arr;
use App\Models\Colony;
use App\Models\Role;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin',
        'current_colony_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_super_admin' => 'boolean',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function hasRole(array|string $roles): bool
    {
        $roles = Arr::wrap($roles);

        return $this->roles()
            ->where(function ($query) use ($roles) {
                $query->whereIn('slug', $roles)
                    ->orWhereIn('name', $roles);
            })
            ->exists();
    }

    public function assignRole(Role|string $role): void
    {
        $roleId = $role instanceof Role
            ? $role->id
            : Role::query()->where('slug', $role)->orWhere('name', $role)->value('id');

        if ($roleId) {
            $this->roles()->syncWithoutDetaching([$roleId]);
        }
    }

    public function hasPermission(string $permissionSlug): bool
    {
        // Super admin has all permissions
        if ($this->is_super_admin) {
            return true;
        }

        // Check permissions in current colony context
        $colonyId = $this->current_colony_id;
        
        return $this->roles()
            ->where(function ($query) use ($colonyId) {
                $query->where('scope', 'global')
                    ->orWhere(function ($q) use ($colonyId) {
                        $q->where('scope', 'colony')
                          ->where('colony_id', $colonyId);
                    });
            })
            ->whereHas('permissions', function ($query) use ($permissionSlug) {
                $query->where('slug', $permissionSlug)
                    ->orWhere('name', $permissionSlug);
            })
            ->exists();
    }

    // Multi-tenant relationships
    public function colonies()
    {
        return $this->belongsToMany(Colony::class, 'user_colonies')
            ->withPivot('role_id', 'is_primary')
            ->withTimestamps();
    }

    public function currentColony()
    {
        return $this->belongsTo(Colony::class, 'current_colony_id');
    }

    public function switchColony($colonyId): bool
    {
        $hasAccess = $this->colonies()->where('colonies.id', $colonyId)->exists();
        
        if ($hasAccess || $this->is_super_admin) {
            $this->current_colony_id = $colonyId;
            $this->save();
            return true;
        }

        return false;
    }

    public function getColonyRole($colonyId = null)
    {
        $colonyId = $colonyId ?? $this->current_colony_id;
        
        $userColony = $this->colonies()
            ->where('colonies.id', $colonyId)
            ->first();

        if ($userColony && $userColony->pivot->role_id) {
            return Role::find($userColony->pivot->role_id);
        }

        return null;
    }
}
