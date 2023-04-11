<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePromotion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 224);
            $table->string('description', 224);
            $table->integer('days');
            $table->string('code', 8);
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->boolean('approved')->default(0);;
            $table->boolean('is_active')->default(1);;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('promotion'))
        {
            Schema::drop('promotion');
        }
    }
}
