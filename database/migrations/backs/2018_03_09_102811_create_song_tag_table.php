<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSongTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('song_id')->unsigned();

            $table->integer('tag_id')->unsigned();

            $table->timestamps();
        });
        Schema::table('song_tag', function (Blueprint $table) {
            //$table->foreign('song_id')->references('id')->on('songs');
            //$table->foreign('tag_id')->references('id')->on('tags');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('song_tag');
    }
}
