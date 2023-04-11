<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEnterprisePerson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprise_person', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('enterprise_id');
            $table->foreign('enterprise_id')->references('id')->on('enterprise');
            $table->string('name', 112);
            $table->string('description', 255)->nullable;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('enterprise_person'))
        {
            Schema::drop('enterprise_person');
        }
    }
}
