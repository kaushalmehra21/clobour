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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_super_admin')) {
                $table->boolean('is_super_admin')->default(false)->after('email');
            }
            if (!Schema::hasColumn('users', 'current_colony_id')) {
                $table->foreignId('current_colony_id')->nullable()->after('is_super_admin')->constrained('colonies')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'current_colony_id')) {
                $table->dropForeign(['current_colony_id']);
            }
            $table->dropColumn(['is_super_admin', 'current_colony_id']);
        });
    }
};
