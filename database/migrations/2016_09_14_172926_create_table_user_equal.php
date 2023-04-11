<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserEqual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_equal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->integer('equal_user_id');
            $table->foreign('equal_user_id')->references('id')->on('user');
            $table->integer('app_id')->default(1);
            $table->foreign('app_id')->references('id')->on('app');
            $table->timestamps();
            $table->index('user_id');
            $table->index('equal_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('user_equal')) {
            Schema::drop('user_equal');
        }
    }
}
