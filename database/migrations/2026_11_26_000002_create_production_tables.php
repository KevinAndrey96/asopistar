<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ponds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('code', 30);
            $table->decimal('area_m2', 10, 2);
            $table->decimal('water_flow_lps', 8, 2);
            $table->string('equipment', 255)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->unique(['user_id', 'code']);
        });

        Schema::create('cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pond_id')->constrained()->cascadeOnDelete();
            $table->foreignId('species_id')->constrained()->restrictOnDelete();
            $table->foreignId('provider_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('stocked_fish');
            $table->decimal('initial_weight_grams', 8, 2);
            $table->date('stocked_on');
            $table->date('closed_on')->nullable();
            $table->timestamps();
            $table->index(['pond_id', 'closed_on']);
        });

        Schema::create('feedings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feed_brand_id')->constrained()->restrictOnDelete();
            $table->date('fed_on');
            $table->decimal('kilograms', 10, 2);
            $table->decimal('protein_percent', 4, 1);
            $table->timestamps();
            $table->index(['cycle_id', 'fed_on']);
        });

        Schema::create('samplings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained()->cascadeOnDelete();
            $table->date('sampled_on');
            $table->unsignedInteger('sample_size');
            $table->decimal('sample_weight_grams', 12, 2);
            $table->unsignedInteger('mortality')->default(0);
            $table->timestamps();
            $table->index(['cycle_id', 'sampled_on']);
        });

        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained()->cascadeOnDelete();
            $table->date('applied_on');
            $table->string('agent', 120);
            $table->string('reason', 500);
            $table->timestamps();
        });

        Schema::create('harvests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cycle_id')->constrained()->cascadeOnDelete();
            $table->date('harvested_on');
            $table->unsignedInteger('fish_count');
            $table->decimal('kilograms', 10, 2);
            $table->string('destination', 120);
            $table->decimal('ice_slaughter_kg', 8, 2)->default(0);
            $table->decimal('ice_cooling_kg', 8, 2)->default(0);
            $table->decimal('ice_transport_kg', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harvests');
        Schema::dropIfExists('treatments');
        Schema::dropIfExists('samplings');
        Schema::dropIfExists('feedings');
        Schema::dropIfExists('cycles');
        Schema::dropIfExists('ponds');
    }
};
