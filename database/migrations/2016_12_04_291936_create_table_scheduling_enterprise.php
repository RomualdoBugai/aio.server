<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSchedulingEnterprise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduling_enterprise', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->boolean('is_active')->default(1);
            $table->integer('scheduling_id');
            $table->foreign('scheduling_id')->references('id')->on('scheduling');
            $table->integer('enterprise_id');
            $table->foreign('enterprise_id')->references('id')->on('enterprise');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('scheduling_enterprise')) {
            Schema::drop('scheduling_enterprise');
        }
    }
}
