<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLead extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name', 225);
            $table->string('phone', 225);
            $table->string('email', 225);
            $table->string('description', 225);
            $table->boolean('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('lead')) {
            Schema::drop('lead');
        }
    }
}
