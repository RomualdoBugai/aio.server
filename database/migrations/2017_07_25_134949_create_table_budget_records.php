<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBudgetRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_records', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('records', 255);
            $table->integer('user_id')->nullable();
            $table->integer('budget_id');
            $table->foreign('budget_id')->references('id')->on('budget');
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
         if (Schema::hasTable('budget_records'))
        {
            Schema::drop('budget_records');
        }
    }
}
