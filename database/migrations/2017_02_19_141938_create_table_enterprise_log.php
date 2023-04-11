<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEnterpriseLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('enterprise_log', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('enterprise_id');
            $table->foreign('enterprise_id')->references('id')->on('enterprise');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->integer('app_id');
            $table->foreign('app_id')->references('id')->on('app');
            $table->string('message', 224);
            $table->string('table', 52)->nullable();
            $table->integer('table_id')->nullable()->default(0);
            $table->index('enterprise_id');
            $table->index('user_id');
            $table->index('app_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        if (Schema::hasTable('enterprise_log')) {
            Schema::drop('enterprise_log');
        }
    }
}
