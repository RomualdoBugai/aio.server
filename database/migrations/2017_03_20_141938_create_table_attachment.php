<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAttachment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('attachment', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('follow_up_id');
            $table->foreign('follow_up_id')->references('id')->on('follow_up');
            $table->string('name', 224);
            $table->string('filename', 32);
            $table->string('size', 48);
            $table->string('format', 48);
            $table->string('path', 48);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        if (Schema::hasTable('attachment')) {
            Schema::drop('attachment');
        }
    }
}
