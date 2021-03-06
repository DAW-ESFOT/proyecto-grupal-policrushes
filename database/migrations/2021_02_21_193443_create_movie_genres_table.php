<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('movieables');
        Schema::dropIfExists('movie_genres');
        Schema::enableForeignKeyConstraints();

        Schema::create('movie_genres', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->text('name')->nullable();
        });

        Schema::create('movieables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movieable_id')->nullable();
            $table->foreign('movieable_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('movieable_type');
            $table->timestamps();
            $table->unsignedBigInteger('movie_genre_id')->nullable();
            $table->foreign('movie_genre_id')->references('id')->on('movie_genres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('movieables');
        Schema::dropIfExists('movie_genres');
        Schema::enableForeignKeyConstraints();
    }
}
