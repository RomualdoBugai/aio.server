<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_client', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->integer('client_user_id');
            $table->foreign('client_user_id')->references('id')->on('user');            
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->index('user_id');
            $table->index('client_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('user_client')) {
            Schema::drop('user_client');
        }
    }
}
