<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'View Dashboard', 'slug' => 'view-dashboard', 'group' => 'dashboard'],
            ['name' => 'Manage Users', 'slug' => 'manage-users', 'group' => 'users'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'group' => 'roles'],
            ['name' => 'Manage Content', 'slug' => 'manage-content', 'group' => 'content'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::query()->updateOrCreate(
                ['slug' => $permissionData['slug']],
                $permissionData
            );
        }

        $roleDefinitions = [
            'admin' => [
                'name' => 'Administrator',
                'description' => 'Full system access',
                'is_default' => true,
                'permissions' => ['view-dashboard', 'manage-users', 'manage-roles', 'manage-content'],
            ],
            'editor' => [
                'name' => 'Editor',
                'description' => 'Content management access',
                'permissions' => ['view-dashboard', 'manage-content'],
            ],
            'viewer' => [
                'name' => 'Viewer',
                'description' => 'Read-only dashboard access',
                'permissions' => ['view-dashboard'],
            ],
        ];

        foreach ($roleDefinitions as $slug => $data) {
            $role = Role::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $data['name'],
                    'slug' => $slug,
                    'description' => $data['description'],
                    'is_default' => $data['is_default'] ?? false,
                ]
            );

            $permissionIds = Permission::query()
                ->whereIn('slug', $data['permissions'])
                ->pluck('id');

            $role->permissions()->sync($permissionIds);
        }

        $firstUser = User::query()->first();

        if (! $firstUser) {
            $firstUser = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ]);
        }

        if (! $firstUser->hasRole('admin')) {
            $firstUser->assignRole('admin');
        }
    }
}
