<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCurrencyQuote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_quote', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->date('day');
            $table->double('rate', 2, 4);
            $table->integer('currency_id');
            $table->foreign('currency_id')->references('id')->on('currency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('currency_quote')) {
            Schema::drop('currency_quote');
        }
    }
}
