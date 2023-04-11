<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserNetwork extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_network', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('network', 255);
            $table->boolean('is_active');
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
        if (Schema::hasTable('user_network')) {
            Schema::drop('user_network');
        }
    }
}
