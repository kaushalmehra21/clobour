<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add colony_id to all tenant-specific tables
        $tenantTables = [
            'units',
            'residents',
            'move_in_out_logs',
            'charges',
            'monthly_bills',
            'payments',
            'expenses',
            'expense_categories',
            'vendors',
            'complaints',
            'complaint_categories',
            'visitors',
            'visitor_logs',
            'vehicles',
            'amenities',
            'booking_slots',
            'bookings',
            'notices',
            'society_settings',
        ];

        foreach ($tenantTables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'colony_id')) {
                        $table->foreignId('colony_id')->nullable()->after('id')->constrained()->onDelete('cascade');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tenantTables = [
            'units',
            'residents',
            'move_in_out_logs',
            'charges',
            'monthly_bills',
            'payments',
            'expenses',
            'expense_categories',
            'vendors',
            'complaints',
            'complaint_categories',
            'visitors',
            'visitor_logs',
            'vehicles',
            'amenities',
            'booking_slots',
            'bookings',
            'notices',
            'society_settings',
        ];

        foreach ($tenantTables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['colony_id']);
                    $table->dropColumn('colony_id');
                });
            }
        }
    }
};
