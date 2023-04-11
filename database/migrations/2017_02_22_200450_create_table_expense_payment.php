<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableExpensePayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('description')->nullable();
            $table->integer('currency_id');
            $table->foreign('currency_id')->references('id')->on('currency');
            $table->integer('expense_id');
            $table->foreign('expense_id')->references('id')->on('expense');
            $table->string('amount', 16);
            $table->string('payment_at', 10);
            $table->integer('delayed_days')->default(0);
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
        if (Schema::hasTable('expense_payment')) {
            Schema::drop('expense_payment');
        }
    }
}
