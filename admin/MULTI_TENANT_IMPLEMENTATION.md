# Multi-Tenant SaaS Implementation Guide

## Overview

This document explains the multi-tenant SaaS architecture implementation for the Colony Management System.

## Architecture

### Single-Database Multi-Tenancy

- All tenants share the same database
- Data isolation via `colony_id` foreign key
- Automatic filtering via global scopes
- Super Admin can access all colonies

## Database Structure

### Core Tables

1. **colonies** - Tenant/Colony information
2. **subscription_plans** - SaaS subscription plans
3. **user_colonies** - Many-to-many relationship between users and colonies
4. **roles** - Updated with `scope` (global/colony) and `colony_id`

### Tenant Tables (with colony_id)

All business tables now include `colony_id`:
- units
- residents
- monthly_bills
- payments
- expenses
- complaints
- visitors
- vehicles
- amenities
- bookings
- notices
- etc.

## Middleware

### 1. SetColonyContext
- Automatically sets colony context for authenticated users
- Super admin can switch colonies via query parameter
- Colony users are assigned to their primary colony

### 2. CheckColonyAccess
- Verifies user has access to the requested colony
- Super admin bypasses checks
- Validates model ownership for resource routes

### 3. CheckPermission
- Checks user permissions in current colony context
- Usage: `middleware('permission:billing.manage')`

### 4. EnsureHasRole (Updated)
- Checks roles in colony context
- Super admin has all roles
- Usage: `middleware('role:colony_admin,manager')`

## Routes

### Super Admin Routes (`/super-admin/*`)
- Colony CRUD
- Subscription plans
- User management
- Analytics & reports
- Impersonation

### Colony Admin Routes (`/colony/*`)
- All existing admin functionality
- Automatically scoped to current colony
- Data isolation enforced

## Models

### Colony Model
```php
$colony->users()
$colony->units()
$colony->residents()
$colony->bills()
$colony->isActive()
```

### User Model (Updated)
```php
$user->is_super_admin
$user->current_colony_id
$user->colonies()
$user->currentColony()
$user->switchColony($colonyId)
$user->getColonyRole($colonyId)
```

### Global Scope Trait
Models can use `HasColonyScope` trait for automatic filtering:
```php
use App\Models\Traits\HasColonyScope;

class Unit extends Model
{
    use HasColonyScope;
}
```

## Implementation Steps

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Create Super Admin
```bash
php artisan tinker
```
```php
$user = User::create([
    'name' => 'Super Admin',
    'email' => 'superadmin@example.com',
    'password' => Hash::make('password'),
    'is_super_admin' => true,
]);
```

### 3. Create Subscription Plans
```php
SubscriptionPlan::create([
    'name' => 'Basic Plan',
    'slug' => 'basic',
    'price' => 999.00,
    'billing_cycle' => 'monthly',
    'max_units' => 100,
    'max_residents' => 500,
]);
```

### 4. Create First Colony
```php
$colony = Colony::create([
    'name' => 'Sample Colony',
    'code' => 'SAMPLE001',
    'plan_id' => 1,
    'status' => 'active',
]);
```

### 5. Assign User to Colony
```php
$user->colonies()->attach($colony->id, [
    'role_id' => $roleId,
    'is_primary' => true,
]);
$user->current_colony_id = $colony->id;
$user->save();
```

## Updating Existing Controllers

All existing controllers need to:
1. Use `HasColonyScope` trait on models (or manually filter)
2. Set `colony_id` when creating records
3. Verify colony access for updates/deletes

Example:
```php
public function store(Request $request)
{
    $validated = $request->validate([...]);
    $validated['colony_id'] = auth()->user()->current_colony_id;
    
    Unit::create($validated);
}
```

## Testing

1. **Login as Super Admin**
   - Access `/super-admin/dashboard`
   - Create colonies
   - View all colonies

2. **Login as Colony Admin**
   - Access `/colony/dashboard`
   - Verify data isolation
   - Create units, residents, etc.

3. **Test Data Isolation**
   - Create data in Colony A
   - Switch to Colony B
   - Verify Colony A data is not visible

## Next Steps

1. Update all existing controllers to set `colony_id`
2. Add `HasColonyScope` trait to tenant models
3. Create views for Super Admin panel
4. Create seeder for default roles and permissions
5. Implement subscription billing logic
6. Add colony switching UI

## Security Notes

- Always verify colony access in middleware
- Never trust client-side colony_id
- Use global scopes for automatic filtering
- Super admin should explicitly opt-in to see all data

