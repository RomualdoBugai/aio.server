<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserEnterprise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_enterprise', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('enterprise_id');
            $table->foreign('enterprise_id')->references('id')->on('enterprise');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('enterprise_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('user_enterprise')) {
            Schema::drop('user_enterprise');
        }
    }
}
