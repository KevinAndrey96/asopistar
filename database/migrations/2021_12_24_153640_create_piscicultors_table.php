<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePiscicultorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('piscicultors', function (Blueprint $table) {
            $table->id();
            $table->string('Nombre');
            $table->string('Email')->unique();
            $table->string('Codigo')->unique();
            $table->string('Predio');
            $table->string('Vereda');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('piscicultors');
    }
}
