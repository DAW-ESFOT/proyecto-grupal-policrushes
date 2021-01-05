<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdColumnFavorit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->unsignedBigInteger('user1_id');
            $table->foreign('user1_id')->references('id')->on('users')->onDelete('restrict');
            $table->unsignedBigInteger('user2_id');
            $table->foreign('user2_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->dropForeign(['user1_id']);
            $table->dropForeign(['user2_id']);
        });
    }
}
