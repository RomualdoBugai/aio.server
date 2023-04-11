<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserPhone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_phone', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('international_code', 4);
            $table->string('long_distance', 4);
            $table->string('number', 12);
            $table->string('default', 20)->nullable();
            $table->string('arm', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->index('number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('user_phone')) {
            Schema::drop('user_phone');
        }
    }
}
