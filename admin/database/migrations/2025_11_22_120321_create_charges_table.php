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
        Schema::create('charges', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Maintenance, Water, Electricity, etc.
            $table->string('slug')->unique();
            $table->enum('type', ['fixed', 'per_sqft', 'per_unit'])->default('fixed');
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('per_sqft_rate', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charges');
    }
};
