<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserPartner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_partner', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->integer('partner_user_id');
            $table->foreign('partner_user_id')->references('id')->on('user');            
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->index('user_id');
            $table->index('partner_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('user_partner')) {
            Schema::drop('user_partner');
        }
    }
}
