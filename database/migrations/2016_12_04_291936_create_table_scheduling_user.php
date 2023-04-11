<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSchedulingUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduling_user', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->boolean('is_active')->default(1);
            $table->integer('scheduling_id');
            $table->foreign('scheduling_id')->references('id')->on('scheduling');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('scheduling_user')) {
            Schema::drop('scheduling_user');
        }
    }
}
