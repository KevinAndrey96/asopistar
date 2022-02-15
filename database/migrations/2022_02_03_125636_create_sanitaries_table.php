<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSanitariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sanitaries', function (Blueprint $table) {
            $table->engine="InnoDB";
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('pond_id')->unsigned();
            $table->string('agent');//desplegle
            $table->text('description');
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
        Schema::dropIfExists('sanitaries');
    }
}
