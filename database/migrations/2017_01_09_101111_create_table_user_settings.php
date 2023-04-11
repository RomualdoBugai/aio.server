<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->default(1);
            $table->foreign('user_id')->references('id')->on('user');
            $table->string('date_format', 32)->default('Y-m-d H:i');
            $table->string('input_date_format', 16)->default("Y-m-d");
            $table->string('timezone', 64)->default("America/Sao_paulo");
            $table->integer('country_id')->default(1);
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
        if (Schema::hasTable('user_settings'))
        {
            Schema::drop('user_settings');
        }
    }
}
