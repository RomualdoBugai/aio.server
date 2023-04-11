<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEnterpriseBranch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprise_branch', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('matrix_enterprise_id');
            $table->foreign('matrix_enterprise_id')->references('id')->on('enterprise');
            $table->integer('branch_enterprise_id');
            $table->foreign('branch_enterprise_id')->references('id')->on('enterprise');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('enterprise_branch')) {
            Schema::drop('enterprise_branch');
        }
    }
}
