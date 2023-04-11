<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSchedulingPerson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduling_person', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->boolean('is_active')->default(1);
            $table->integer('scheduling_id');
            $table->foreign('scheduling_id')->references('id')->on('scheduling');
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
        if (Schema::hasTable('scheduling_person')) {
            Schema::drop('scheduling_person');
        }
    }
}
