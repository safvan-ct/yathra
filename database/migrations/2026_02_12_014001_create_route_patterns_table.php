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
        Schema::create('stops', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();

            $table->string('code')->unique();

            // If stop have same name then ideantify use this
            $table->string('local_governing_body')->nullable();
            $table->string('legislative_assembly')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode', 10)->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->boolean('is_bus_terminal')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->index(['name', 'district']);
        });

        Schema::create('route_patterns', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('info')->nullable()->index();

            $table->foreignId('origin_stop_id')->constrained('stops')->onDelete('cascade');
            $table->foreignId('destination_stop_id')->constrained('stops')->onDelete('cascade');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        Schema::create('route_pattern_stops', function (Blueprint $table) {
            $table->id();

            $table->foreignId('route_pattern_id')->constrained()->onDelete('cascade');

            $table->foreignId('stop_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('stop_order')->default(0);

            $table->unsignedInteger('default_offset_minutes')->default(5);

            $table->unique(['route_pattern_id', 'stop_order']);
            $table->unique(['route_pattern_id', 'stop_id']);

            $table->index(['route_pattern_id', 'stop_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_pattern_stops');
        Schema::dropIfExists('route_patterns');
        Schema::dropIfExists('stops');
    }
};
