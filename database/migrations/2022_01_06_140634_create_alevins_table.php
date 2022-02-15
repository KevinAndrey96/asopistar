<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlevinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alevins', function (Blueprint $table) {
            $table->engine="InnoDB";
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('pond_id')->unsigned();
            $table->string('species');//lista
            $table->double('amount');
            $table->string('source');//lista
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
        Schema::dropIfExists('alevins');
    }
}
