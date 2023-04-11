<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->string("code", 48);
            $table->decimal('amount_total', 15,2);
            $table->integer('quantity_total');
            $table->json('json');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->integer('payment_method_id');
            $table->foreign('payment_method_id')->references('id')->on('payment_method');
            $table->integer('order_status_id');
            $table->foreign('order_status_id')->references('id')->on('order_status');
            $table->integer('app_id');
            $table->foreign('app_id')->references('id')->on('app');
            $table->integer('plan_id');
            $table->foreign('plan_id')->references('id')->on('plan');
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
        Schema::drop('order');
    }
}
