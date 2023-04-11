<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRecoverPassword extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recover_password', function (Blueprint $table) {
            $table->increments('id');
            $table->string('verify', 32);
            $table->integer('app_id');
            $table->foreign('app_id')->references('id')->on('app');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->boolean('is_active')->default(1);
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
        if (Schema::hasTable('recover_password'))
        {
            Schema::drop('recover_password');
        }
    }
}
