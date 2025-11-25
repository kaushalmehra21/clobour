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
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Clubhouse, Gym, Swimming Pool, etc.
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('booking_fee', 10, 2)->default(0);
            $table->integer('max_advance_booking_days')->default(30);
            $table->integer('min_advance_booking_hours')->default(2);
            $table->boolean('requires_approval')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('available_days')->nullable(); // ['monday', 'tuesday', ...]
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->integer('max_booking_duration_hours')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
