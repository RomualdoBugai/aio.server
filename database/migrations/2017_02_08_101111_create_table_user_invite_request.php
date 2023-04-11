<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserInviteRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_invite_request', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->default(1);
            $table->foreign('user_id')->references('id')->on('user');
            $table->string('email', '112');
            $table->string('name', '112');
            $table->string('token', 32);
            $table->integer('app_id');
            $table->foreign('app_id')->references('id')->on('app');
            $table->boolean('is_active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('user_invite_request'))
        {
            Schema::drop('user_invite_request');
        }
    }
}
