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
        Schema::create('trip_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_direction_id')->constrained();
            $table->foreignId('bus_id')->constrained();
            $table->time('departure_time');
            $table->json('days_of_week'); // ["mon","tue","wed"]
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['route_direction_id', 'bus_id', 'departure_time'], 'unique_ts_identity');
        });

        Schema::create('trip_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_schedule_id')->constrained();
            $table->date('trip_date');
            $table->enum('status', ['scheduled', 'running', 'completed', 'cancelled']);
            $table->timestamp('actual_departure_time')->nullable();
            $table->timestamps();

            $table->unique(['trip_schedule_id', 'trip_date']);
            $table->index(['trip_date']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_instances');
        Schema::dropIfExists('trip_schedules');
    }
};
