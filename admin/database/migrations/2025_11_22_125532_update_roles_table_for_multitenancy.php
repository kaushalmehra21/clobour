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
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'scope')) {
                $table->enum('scope', ['global', 'colony'])->default('colony')->after('is_default');
            }
            if (!Schema::hasColumn('roles', 'colony_id')) {
                $table->foreignId('colony_id')->nullable()->after('scope')->constrained()->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'colony_id')) {
                $table->dropForeign(['colony_id']);
            }
            $table->dropColumn(['scope', 'colony_id']);
        });
    }
};
