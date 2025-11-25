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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_number')->unique(); // A-101, B-202, etc.
            $table->string('block')->nullable(); // Block A, B, C
            $table->string('floor')->nullable();
            $table->enum('type', ['flat', 'shop', 'office', 'parking'])->default('flat');
            $table->decimal('area', 10, 2)->nullable(); // in sqft
            $table->enum('status', ['occupied', 'vacant', 'under_construction'])->default('vacant');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
