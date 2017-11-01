<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('search_type');
            $table->string('search_mode');
            $table->string('status');
            $table->string('title');
            $table->integer('character_count');
            $table->integer('word_count');
            $table->integer('sentence_count');
            $table->integer('matching_sentences');
            $table->integer('matching_sources');
            $table->double('plagiarism_percentage');
            $table->longText('content');
            $table->json('search_result');
            $table->string('pdf_report');            
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
        Schema::dropIfExists('reports');
    }
}
