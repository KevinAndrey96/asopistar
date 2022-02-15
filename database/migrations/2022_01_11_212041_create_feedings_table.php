<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedings', function (Blueprint $table) {
            $table->engine="InnoDB";
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('pond_id')->unsigned();
            $table->double('amount');//anexar kg
            $table->string('stage');
            $table->string('age');
            $table->integer('number_of_fish');
            $table->double('average_weight');
            $table->double('protein');//sale de la marca
            $table->string('mark');//lista
            $table->date('date_of_entry');//->nullable()->default(new DateTime());
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete("cascade");
            $table->foreign('pond_id')->references('id')->on('ponds')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedings');
    }
}
