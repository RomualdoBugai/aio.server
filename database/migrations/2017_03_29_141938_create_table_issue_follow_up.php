<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableIssueFollowUp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('issue_follow_up', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->integer('issue_id');
            $table->foreign('issue_id')->references('id')->on('issue');
            $table->longText('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        if (Schema::hasTable('issue_follow_up')) {
            Schema::drop('issue_follow_up');
        }
    }
}
