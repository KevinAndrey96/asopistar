<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ices', function (Blueprint $table) {
            $table->engine="InnoDB";
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('pond_id')->unsigned();
            $table->string('species');
            $table->double('fishing_amount');
            $table->double('sacrifice_amount');
            $table->double('cooled_amount');
            $table->double('transport_amount');
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
        Schema::dropIfExists('ices');
    }
}
