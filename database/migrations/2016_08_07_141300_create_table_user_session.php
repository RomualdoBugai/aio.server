<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserSession extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_session', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip_address', 48);
            $table->json('info');
            $table->boolean('is_active');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->integer('app_id');
            $table->foreign('app_id')->references('id')->on('app');
            $table->timestamps();
            $table->index('user_id');
            $table->index('app_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('user_session'))
        {
            Schema::drop('user_session');
        }
    }
}
