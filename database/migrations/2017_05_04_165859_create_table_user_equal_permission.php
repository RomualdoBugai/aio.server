<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserEqualPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_equal_permission', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_equal_id');
            $table->foreign('user_equal_id')->references('id')->on('user_equal');
            $table->integer('enterprise_id');
            $table->foreign('enterprise_id')->references('id')->on('enterprise');
            $table->json('actions')->default(json_encode(array()));
            $table->timestamps();
            $table->boolean('is_active')->default(1);
            $table->index('user_equal_id');
            $table->index('enterprise_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('user_equal_permission')) {
            Schema::drop('user_equal_permission');
        }
    }
}
