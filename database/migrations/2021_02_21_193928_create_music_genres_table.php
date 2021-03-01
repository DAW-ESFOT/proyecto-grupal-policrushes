<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('musicables');
        Schema::dropIfExists('music_genres');
        Schema::enableForeignKeyConstraints();

        Schema::create('music_genres', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->text('name')->nullable();
        });

        Schema::create('musicables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('musicable_id')->nullable();
            $table->foreign('musicable_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('musicable_type');
            $table->timestamps();
            $table->unsignedBigInteger('music_genre_id')->nullable();
            $table->foreign('music_genre_id')->references('id')->on('music_genres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('musicables');
        Schema::dropIfExists('music_genres');
        Schema::enableForeignKeyConstraints();
    }
}
