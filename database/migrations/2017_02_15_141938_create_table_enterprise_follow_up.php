<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEnterpriseFollowUp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprise_follow_up', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('enterprise_id');
            $table->foreign('enterprise_id')->references('id')->on('enterprise');
            $table->integer('follow_up_id');
            $table->foreign('follow_up_id')->references('id')->on('follow_up');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('enterprise_follow_up'))
        {
            Schema::drop('enterprise_follow_up');
        }
    }
}
