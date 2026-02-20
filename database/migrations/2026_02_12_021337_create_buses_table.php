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
        Schema::create('operators', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('phone');
            $table->string('pin');
            $table->enum('type', ['private', 'village', 'city', 'state', 'national', 'international'])->default('private');
            $table->enum('auth_status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['name', 'phone']);
        });

        Schema::create('buses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('operator_id')->constrained()->cascadeOnDelete();

            $table->string('bus_number')->unique();
            $table->string('slug')->unique();
            $table->string('bus_name');
            $table->string('bus_color')->nullable();
            $table->enum('auth_status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        // Schema::create('trips', function (Blueprint $table) {
        //     $table->id();

        //     $table->foreignId('route_pattern_id')->constrained()->cascadeOnDelete();
        //     $table->foreignId('bus_id')->nullable()->constrained()->nullOnDelete();
        //     $table->time('start_time');
        //     $table->foreignId('final_stop_id')->constrained('stops');

        //     $table->enum('service_type', ['ordinary', 'limited', 'fast', 'super_fast', 'express'])->default('ordinary');

        //     $table->boolean('is_active')->default(true);

        //     $table->timestamps();

        //     $table->index(['route_pattern_id', 'start_time']);
        //     $table->index(['final_stop_id']);
        // });

        // Schema::create('trip_stop_overrides', function (Blueprint $table) {
        //     $table->id();

        //     $table->foreignId('trip_id')->constrained()->cascadeOnDelete();

        //     $table->foreignId('stop_id')->constrained()->cascadeOnDelete();

        //     $table->boolean('is_skipped')->default(false);
        //     $table->unsignedInteger('custom_offset_minutes')->nullable();

        //     $table->unique(['trip_id', 'stop_id']);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('trip_stop_overrides');
        // Schema::dropIfExists('trips');
        Schema::dropIfExists('buses');
        Schema::dropIfExists('operators');
    }
};
