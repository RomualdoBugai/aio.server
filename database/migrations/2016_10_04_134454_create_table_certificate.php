<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCertificate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('password', 255);
            $table->string('pfx_file', 255);
            $table->string('crt_file', 255);
            $table->string('hash', 255);
            $table->longtext('data')->nullable();
            $table->timestamp('valid_from');
            $table->timestamp('valid_to');
            $table->integer('enterprise_id');
            $table->foreign('enterprise_id')->references('id')->on('enterprise');
            $table->timestamps();
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificate');
    }
}
