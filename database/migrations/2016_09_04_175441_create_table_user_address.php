<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_address', function (Blueprint $table) {
            $table->increments('id');
            $table->string('street', 96);
            $table->string('number', 8)->nullable();
            $table->string('district', 96);
            $table->string('city', 112);
            $table->string('state', 2);
            $table->string('postal_code', 8);
            $table->string('complement', 48)->nullable();
            $table->integer('country_id')->default(1);
            $table->foreign('country_id')->references('id')->on('country');
            $table->boolean('is_active');
            $table->boolean('default');
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
        if (Schema::hasTable('user_address'))
        {
            Schema::drop('user_address');
        }
    }
}
