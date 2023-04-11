<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBlockedUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocked_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description', 255)->nullable();
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->integer('app_id');
            $table->foreign('app_id')->references('id')->on('app');
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
        Schema::dropIfExists('blocked_user');
    }
}
