<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePersonPhone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_phone', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('international_code', 4);
            $table->string('long_distance', 4);
            $table->string('number', 12);
            $table->string('default', 20)->nullable();
            $table->string('arm', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('person_id');
            $table->foreign('person_id')->references('id')->on('person');
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
        if (Schema::hasTable('person_phone')) {
            Schema::drop('person_phone');
        }
    }
}
