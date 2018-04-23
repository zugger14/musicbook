<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLyricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lyrics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('lyrics_content');
            
            $table->integer('song_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('lyrics', function (Blueprint $table) {
           // $table->foreign('song_id')->references('id')->on('songs');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lyrics');
    }
}
