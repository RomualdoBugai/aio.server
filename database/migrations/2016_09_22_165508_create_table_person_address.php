<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePersonAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_address', function (Blueprint $table) {
            $table->increments('id');
            $table->string('street', 96);
            $table->string('number', 8);
            $table->string('district', 96);
            $table->string('city', 112);
            $table->string('state', 2);
            $table->string('postal_code', 8);
            $table->string('complement', 48);
            $table->integer('country_id')->default(1);
            $table->foreign('country_id')->references('id')->on('country');
            $table->boolean('is_active');
            $table->boolean('default');
            $table->integer('person_id');
            $table->foreign('person_id')->references('id')->on('person');
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
        if (Schema::hasTable('person_address')) {
            Schema::drop('person_address');
        }
    }
}
