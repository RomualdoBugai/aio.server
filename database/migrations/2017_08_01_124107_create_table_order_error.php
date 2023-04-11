<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrderError extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_error', function (Blueprint $table) {
            $table->increments('id');
            $table->json('json');
            $table->integer('order_id');
            $table->foreign('order_id')->references('id')->on('order');
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
        if (Schema::hasTable('order_error'))
        {
            Schema::drop('order_error');
        }
    }
}
