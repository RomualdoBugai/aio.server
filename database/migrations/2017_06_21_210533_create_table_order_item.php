<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrderItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity');
            $table->decimal('amount', 15,2);
            $table->string('description', 224)->nullable();
            $table->string('product_code', 5);
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
        Schema::drop('order_item');
    }
}
