<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEnterpriseAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprise_address', function (Blueprint $table) {
            $table->increments('id');
            $table->string('street', 96);
            $table->string('number', 8);
            $table->string('district', 96);
            $table->string('city', 112);
            $table->string('state', 2);
            $table->string('postal_code', 8);
            $table->string('complement', 48);
            $table->boolean('is_active');
            $table->boolean('default');
            $table->integer('enterprise_id');
            $table->foreign('enterprise_id')->references('id')->on('enterprise');
            $table->integer('country_id')->default(1);
            $table->foreign('country_id')->references('id')->on('country');
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
        if (Schema::hasTable('enterprise_address')) {
            Schema::drop('enterprise_address');
        }
    }
}
