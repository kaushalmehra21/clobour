<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Colony;
use App\Models\SubscriptionPlan;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class MultiTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'is_super_admin' => true,
            ]
        );

        // Create Subscription Plans
        $basicPlan = SubscriptionPlan::firstOrCreate(
            ['slug' => 'basic'],
            [
                'name' => 'Basic Plan',
                'description' => 'Basic plan for small colonies',
                'price' => 999.00,
                'billing_cycle' => 'monthly',
                'max_units' => 100,
                'max_residents' => 500,
                'max_staff' => 10,
                'trial_days' => 14,
                'is_active' => true,
            ]
        );

        $premiumPlan = SubscriptionPlan::firstOrCreate(
            ['slug' => 'premium'],
            [
                'name' => 'Premium Plan',
                'description' => 'Premium plan for large colonies',
                'price' => 2499.00,
                'billing_cycle' => 'monthly',
                'max_units' => 500,
                'max_residents' => 2500,
                'max_staff' => 50,
                'trial_days' => 30,
                'is_active' => true,
            ]
        );

        // Create Sample Colony
        $colony = Colony::firstOrCreate(
            ['code' => 'DEMO001'],
            [
                'name' => 'Demo Colony',
                'address' => '123 Main Street',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400001',
                'phone' => '+91-1234567890',
                'email' => 'demo@colony.com',
                'plan_id' => $basicPlan->id,
                'status' => 'active',
                'max_units' => 100,
                'max_residents' => 500,
            ]
        );

        // Create Colony Roles
        $colonyAdminRole = Role::firstOrCreate(
            ['slug' => 'colony_admin', 'colony_id' => $colony->id],
            [
                'name' => 'Colony Admin',
                'description' => 'Full access to colony management',
                'scope' => 'colony',
                'is_default' => true,
            ]
        );

        $managerRole = Role::firstOrCreate(
            ['slug' => 'manager', 'colony_id' => $colony->id],
            [
                'name' => 'Manager',
                'description' => 'Manager access',
                'scope' => 'colony',
            ]
        );

        $accountantRole = Role::firstOrCreate(
            ['slug' => 'accountant', 'colony_id' => $colony->id],
            [
                'name' => 'Accountant',
                'description' => 'Billing and accounting access',
                'scope' => 'colony',
            ]
        );

        // Create Colony Admin User
        $colonyAdmin = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Colony Admin',
                'password' => Hash::make('password'),
                'is_super_admin' => false,
                'current_colony_id' => $colony->id,
            ]
        );

        // Assign user to colony
        $colonyAdmin->colonies()->syncWithoutDetaching([
            $colony->id => [
                'role_id' => $colonyAdminRole->id,
                'is_primary' => true,
            ]
        ]);

        $this->command->info('Multi-tenant setup completed!');
        $this->command->info('Super Admin: superadmin@example.com / password');
        $this->command->info('Colony Admin: admin@demo.com / password');
    }
}
