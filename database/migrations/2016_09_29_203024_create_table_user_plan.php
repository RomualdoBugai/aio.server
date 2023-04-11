<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_plan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('plan_id');
            $table->foreign('plan_id')->references('id')->on('plan');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->date('started_at');
            $table->date('end_at');
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
        if (Schema::hasTable('user_plan'))
        {
            Schema::drop('user_plan');
        }
    }
}
