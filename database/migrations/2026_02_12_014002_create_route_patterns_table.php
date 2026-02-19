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

            $table->string('name', 120)->index();
            $table->string('code', 120)->nullable();

            $table->foreignId('origin_stop_id')->constrained('stops');
            $table->foreignId('destination_stop_id')->constrained('stops');

            $table->decimal('distance_km', 8, 2)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Generated columns
            $table->unsignedBigInteger('stop_min')
                ->storedAs('LEAST(origin_stop_id, destination_stop_id)');

            $table->unsignedBigInteger('stop_max')
                ->storedAs('GREATEST(origin_stop_id, destination_stop_id)');

            // Unique normalized pair
            $table->unique(['stop_min', 'stop_max'], 'unique_route_pair');

            $table->index(['origin_stop_id', 'destination_stop_id']);

            $table->unique(['origin_stop_id', 'destination_stop_id'], 'unique_rp_identity');
        });

        Schema::create('route_directions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('route_pattern_id')->constrained()->cascadeOnDelete();

            $table->string('name', 120)->default('');
            $table->string('direction', 120); // UP / DOWN

            $table->foreignId('origin_stop_id')->constrained('stops');
            $table->foreignId('destination_stop_id')->constrained('stops');

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['route_pattern_id', 'name', 'direction'], 'unique_rd_identity');
            $table->unique(['origin_stop_id', 'destination_stop_id'], 'unique_rd2_identity');

            $table->index(['route_pattern_id', 'direction']);
        });

        Schema::create('route_direction_stops', function (Blueprint $table) {
            $table->id();

            $table->foreignId('route_direction_id')->constrained()->onDelete('cascade');

            $table->foreignId('stop_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('stop_order')->default(0);

            $table->unsignedInteger('minutes_from_previous_stop')->default(2);
            $table->unsignedInteger('default_offset_minutes')->default(5);

            $table->decimal('distance_from_origin', 8, 2)->nullable();
            $table->boolean('is_active')->default(true);

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
        Schema::dropIfExists('route_direction_stops');
        Schema::dropIfExists('route_directions');
        Schema::dropIfExists('route_patterns');
    }
};
