<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEnterpriseCertificate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprise_certificate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 96);
            $table->string('pfx_file', 224);
            $table->string('crt_file', 224);
            $table->string('password', 96);
            $table->timestamp("valid_from");
            $table->timestamp("valid_to");
            $table->integer('enterprise_id');
            $table->foreign('enterprise_id')->references('id')->on('enterprise');
            $table->timestamps();
            $table->index('enterprise_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('enterprise_certificate'))
        {
            Schema::drop('enterprise_certificate');
        }
    }
}
