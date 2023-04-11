<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableIssue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 224);
            $table->longText('text');
            $table->integer('app_id');
            $table->foreign('app_id')->references('id')->on('app');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->integer('issue_type_id');
            $table->foreign('issue_type_id')->references('id')->on('issue_type');
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
        if (Schema::hasTable('issue'))
        {
            Schema::drop('issue');
        }
    }
}
