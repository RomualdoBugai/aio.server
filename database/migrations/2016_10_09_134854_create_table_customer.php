<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCustomer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table', 10);
            $table->integer('person_id')->nullable()->unsigned();
            $table->foreign('person_id')->references('id')->on('person');
            $table->integer('enterprise_id')->nullable()->unsigned();
            $table->foreign('enterprise_id')->references('id')->on('enterprise');
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
        Schema::dropIfExists('customer');
    }
}
