<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePersonEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_email', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('email', 128);
            $table->boolean('is_active');
            $table->integer('person_id');
            $table->foreign('person_id')->references('id')->on('person');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('person_email')) {
            Schema::drop('person_email');
        }
    }
}
