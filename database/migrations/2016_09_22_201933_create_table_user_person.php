<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserPerson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_person', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('person_id');
            $table->foreign('person_id')->references('id')->on('person');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
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
        if (Schema::hasTable('user_person'))
        {
            Schema::drop('user_person');
        }
    }
}
        