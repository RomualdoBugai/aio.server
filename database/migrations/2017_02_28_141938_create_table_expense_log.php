<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableExpenseLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('expense_log', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('expense_id');
            $table->foreign('expense_id')->references('id')->on('expense');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->integer('app_id');
            $table->foreign('app_id')->references('id')->on('app');
            $table->string('message', 224);
            $table->string('table', 52)->nullable();
            $table->integer('table_id')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        if (Schema::hasTable('expense_log')) {
            Schema::drop('expense_log');
        }
    }
}
