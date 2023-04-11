<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableExpense extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 224);
            $table->longText('description')->nullable();
            $table->integer('currency_id');
            $table->foreign('currency_id')->references('id')->on('currency');
            $table->integer('bank_account_id');
            $table->foreign('bank_account_id')->references('id')->on('bank_account');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            #$table->decimal('amount', 15, 2);
            $table->string('amount', 17);
            $table->string('due_date_at', 10);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('expense')) {
            Schema::drop('expense');
        }
    }
}
