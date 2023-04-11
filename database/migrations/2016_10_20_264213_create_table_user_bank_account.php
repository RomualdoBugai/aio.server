<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserBankAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bank_account', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bank_account_id');
            $table->foreign('bank_account_id')->references('id')->on('bank_account');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
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
        if (Schema::hasTable('user_bank_account')) {
            Schema::drop('user_bank_account');
        }
    }
}
