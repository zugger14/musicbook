<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('user_id')->unsigned()->nullable();

            $table->integer('album_id')->unsigned()->nullable();

            $table->integer('lyrics_id')->unsigned()->nullable();
            $table->double('length')->nullable();

            $table->string('song_filename');
            $table->string('song_description')->nullable();




            $table->string('image')->nullable();


            $table->timestamps();
        });

        Schema::table('songs', function (Blueprint $table) {
            //$table->foreign('album_id')->references('id')->on('albums');
            //$table->foreign('artist_id')->references('id')->on('artists');
            //$table->foreign('lyrics_id')->references('id')->on('lyrics');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('songs');
    }
}
