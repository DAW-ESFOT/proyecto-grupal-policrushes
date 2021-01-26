<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMusicGendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('music_genders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('name')->nullable();
        });

        Schema::create('music_user', function (Blueprint $table) {
            $table->unsignedBigInteger('music_gender_id');
            $table->foreign('music_gender_id')->references('id')->on('music_genders')->onDelete('restrict');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
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
        Schema::dropIfExists('music_user');
        Schema::dropIfExists('music_genders');
        Schema::enableForeignKeyConstraints();
    }
}
