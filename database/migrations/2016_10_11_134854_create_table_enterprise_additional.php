<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEnterpriseAdditional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprise_additional', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('municipal_registration', 20)->nullable();
            $table->string('estadual_registration', 20)->nullable();
            $table->string('encouraging_cultural', 20)->nullable();
            $table->string('tax_regime', 20)->nullable();
            $table->string('national_simple', 20)->nullable();
            $table->string('lot', 20)->nullable();
            $table->string('note', 20)->nullable();
            $table->string('operation_nature', 128)->nullable();
            $table->string('activity', 128)->nullable();
            $table->integer('enterprise_id');
            $table->foreign('enterprise_id')->references('id')->on('enterprise');
            $table->longText('logo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('enterprise_additional')) {
            Schema::drop('enterprise_additional');
        }
    }
}
