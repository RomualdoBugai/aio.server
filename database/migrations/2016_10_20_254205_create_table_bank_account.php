<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBankAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_account', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_active');
            $table->integer('bank_id');
            $table->foreign('bank_id')->references('id')->on('bank');
            $table->string('name', 72)->default('undefined');
            $table->string('agency_number', 8);
            $table->string('agency_number_digit', 2);
            $table->string('account_number', 8);
            $table->string('account_number_digit', 2);
            $table->decimal('opening_balance');
            $table->date('opening_at');
            $table->boolean('is_savings_account');
            $table->boolean('is_current_account');
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
        if (Schema::hasTable('bank_account')) {
            Schema::drop('bank_account');
        }
    }
}
