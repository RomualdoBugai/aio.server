<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePersonNetwork extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_network', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('network', 255);
            $table->boolean('is_active');
            $table->integer('person_id');
            $table->foreign('person_id')->references('id')->on('person');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('person_network')) {
            Schema::drop('person_network');
        }
    }
}
