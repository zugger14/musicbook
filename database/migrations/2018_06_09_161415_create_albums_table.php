<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('playlist_id')->unsigned();
            $table->date('release_date');
            $table->timestamps();

            $table->foreign('playlist_id')->references('id')->on('playlists')->onDelete('cascade');

        });

        Schema::table('playlists', function (Blueprint $table) {
            $table->string('playlist_type');
            //$table->integer('album_id')->unsigned();
            //$table->foreign('album_id')->references('id')->on('albums')->onDelete('cascade');

        });

        Schema::table('songs', function (Blueprint $table) {
            $table->foreign('album_id')->references('id')->on('albums')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('albums');
        Schema::table('playlists', function (Blueprint $table) {
            $table->dropColumn('playlist_type');
        });
        Schema::table('songs', function (Blueprint $table) {
            $table->dropForeign('album_id');
        });
    }
}
