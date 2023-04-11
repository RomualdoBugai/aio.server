<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEnterprisePhone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprise_phone', function (Blueprint $table) {
            $table->increments('id');
            $table->string('international_code', 4);
            $table->string('long_distance', 4);
            $table->string('number', 12);
            $table->string('default', 20)->nullable();
            $table->string('arm', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('enterprise_id');
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
        Schema::dropIfExists('enterprise_phone');
    }
}
