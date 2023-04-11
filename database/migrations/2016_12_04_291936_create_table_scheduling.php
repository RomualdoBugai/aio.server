<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableScheduling extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduling', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title', 112);
            $table->longText('description');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('coordinates', 48)->default('0,0');
            $table->boolean('is_public')->default(1);
            $table->boolean('is_active');
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('scheduling')) {
            Schema::drop('scheduling');
        }
    }
}
