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
        Schema::create('route_patterns', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('code')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        Schema::create('route_directions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_pattern_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // UP / DOWN
            $table->string('info')->nullable();
            $table->foreignId('origin_stop_id')->constrained('stops');
            $table->foreignId('destination_stop_id')->constrained('stops');
            $table->timestamps();
        });

        Schema::create('route_pattern_stops', function (Blueprint $table) {
            $table->id();

            $table->foreignId('route_direction_id')->constrained()->onDelete('cascade');

            $table->foreignId('stop_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('stop_order')->default(0);

            $table->unsignedInteger('minutes_from_previous_stop')->default(2);
            $table->unsignedInteger('default_offset_minutes')->default(5);

            $table->unique(['route_direction_id', 'stop_order']);
            $table->unique(['route_direction_id', 'stop_id']);

            $table->index(['route_direction_id', 'stop_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_pattern_stops');
        Schema::dropIfExists('route_directions');
        Schema::dropIfExists('route_patterns');
    }
};
