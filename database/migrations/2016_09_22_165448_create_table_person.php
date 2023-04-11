<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePerson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person', function (Blueprint $table) {
            $table->increments('id');
            $table->string('national_code', 16)->nullable();
            $table->string('name', 128);
            $table->string('alias', 128)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('gender')->nullable();
            $table->boolean('is_active');
            $table->timestamps();
            $table->index('name');
            $table->index('national_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('person')) {
            Schema::drop('person');
        }
    }
}
