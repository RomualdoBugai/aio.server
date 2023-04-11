<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('api', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token', 32);
            $table->timestamps();
            $table->index('token');
        });

        $api = new \App\Models\Api;
        $api->token = md5('william');
        $api->save();
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasTable('api'))
        {
            Schema::drop('api');
        }
    }
}
