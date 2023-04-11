<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePagSeguro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pag_seguro', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 96);
            $table->string('fee_amount', 17);
            $table->string('net_amount', 17);
            $table->string('extra_amount', 17);
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
        if (Schema::hasTable('pag_seguro'))
        {
            Schema::drop('pag_seguro');
        }
    }
}
