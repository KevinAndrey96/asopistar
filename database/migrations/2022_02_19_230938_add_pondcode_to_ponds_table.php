<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPondcodeToPondsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ponds', function (Blueprint $table) {
            //
            $table->string('pondcode')->nullable()->after('user_id');
            $table->date('finish_date')->nullable()->after('tools');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ponds', function (Blueprint $table) {
            //
            $table->dropColumn('pondcode');
            $table->dropColumn('finish_date');
        });
    }
}
