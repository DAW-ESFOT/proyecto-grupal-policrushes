<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('birthdate')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->text('gender')->nullable();
            $table->integer('age')->nullable();
            $table->text('description')->nullable();
            $table->point('location')->nullable();
            $table->text('address')->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->text('preferred_gender')->nullable();
            $table->text('preferred_pet')->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->decimal('lat', 11, 8)->nullable();
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
        Schema::dropIfExists('users');
        Schema::enableForeignKeyConstraints();
    }
}
