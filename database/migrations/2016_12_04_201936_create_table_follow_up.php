<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFollowUp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow_up', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->longText('description');
            $table->integer('reason_id')->default(1);
            $table->foreign('reason_id')->references('id')->on('follow_up_reason');
            $table->integer('user_id')->default(1);
            $table->foreign('user_id')->references('id')->on('user');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('follow_up')) {
            Schema::drop('follow_up');
        }
    }
}
