<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEnterprise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprise', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 96);
            $table->string('fantasy_name', 96)->nullable();;
            $table->string('national_code', 16)->unique()->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('open_at', 10)->nullable();
            $table->string('legal_nature', 224)->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('is_matrix')->default(true);
            $table->string('last_update', 48)->nullable();
            $table->integer('country_id')->default(1);
            $table->foreign('country_id')->references('id')->on('country');
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
        if (Schema::hasTable('enterprise'))
        {
            Schema::drop('enterprise');
        }
    }
}
