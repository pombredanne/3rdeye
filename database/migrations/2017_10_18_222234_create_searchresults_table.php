<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchresultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('searchresults', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('report_id');
            $table->integer('reference_id');
            $table->longText('match');
            $table->integer('page');
            $table->integer('offset');
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
        Schema::dropIfExists('searchresults');
    }
}
