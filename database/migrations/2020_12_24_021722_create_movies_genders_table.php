<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesGendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_genders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('name')->nullable();
        });

        Schema::create('movie_user', function (Blueprint $table) {
            $table->unsignedBigInteger('movie_gender_id');
            $table->foreign('movie_gender_id')->references('id')->on('movie_genders')->onDelete('restrict');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('movie_user');
        Schema::dropIfExists('movie_genders');
        Schema::enableForeignKeyConstraints();
    }
}
