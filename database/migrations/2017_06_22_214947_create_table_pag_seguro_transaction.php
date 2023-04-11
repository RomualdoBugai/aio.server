<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePagSeguroTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pag_seguro_transaction', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 128);
            $table->string('status', 1);
            $table->string('name', 32);
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
        if (Schema::hasTable('pag_seguro_transaction'))
        {
            Schema::drop('pag_seguro_transaction');
        }
    }
}
