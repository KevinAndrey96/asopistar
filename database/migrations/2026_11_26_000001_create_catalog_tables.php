<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('species', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->unique();
            $table->string('scientific_name', 120)->nullable();
            $table->unsignedSmallInteger('fry_until_week');
            $table->unsignedSmallInteger('grow_out_until_week');
            $table->unsignedSmallInteger('harvest_week');
            $table->unsignedSmallInteger('market_weight_grams');
            $table->timestamps();
        });

        Schema::create('feed_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->unique();
            $table->decimal('protein_percent', 4, 1);
            $table->timestamps();
        });

        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('nit', 16)->unique();
            $table->string('city', 80);
            $table->string('phone', 20)->nullable();
            $table->string('contact_name', 120)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('providers');
        Schema::dropIfExists('feed_brands');
        Schema::dropIfExists('species');
    }
};
