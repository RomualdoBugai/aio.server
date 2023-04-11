<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBudget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('name', 112);
            $table->string('email', 112); 
            $table->string('phone', 20);
            $table->integer('budget_status_id');
            $table->foreign('budget_status_id')->references('id')->on('budget_status');
            $table->integer('app_id');
            $table->foreign('app_id')->references('id')->on('app');
            $table->integer('user_id')->nullable();
            $table->integer('plan_id')->nullable();
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
        if (Schema::hasTable('budget'))
        {
            Schema::drop('budget');
        }
    }
}
