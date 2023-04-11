<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAppSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('app_support', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('app_id');
            $table->foreign('app_id')->references('id')->on('app');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        # logfiscal
        $app            = new \App\Models\AppSupport;
        $app->app_id    = 1;
        $app->user_id   = 1;
        $app->is_active = 1;
        $app->save();

        $app            = new \App\Models\AppSupport;
        $app->app_id    = 1;
        $app->user_id   = 2;
        $app->is_active = 1;
        $app->save();

        $app            = new \App\Models\AppSupport;
        $app->app_id    = 1;
        $app->user_id   = 3;
        $app->is_active = 1;
        $app->save();

        # lognfse
        $app            = new \App\Models\AppSupport;
        $app->app_id    = 2;
        $app->user_id   = 1;
        $app->is_active = 1;
        $app->save();

        $app            = new \App\Models\AppSupport;
        $app->app_id    = 2;
        $app->user_id   = 2;
        $app->is_active = 1;
        $app->save();

        $app            = new \App\Models\AppSupport;
        $app->app_id    = 2;
        $app->user_id   = 3;
        $app->is_active = 1;
        $app->save();

        # lumbex
        $app            = new \App\Models\AppSupport;
        $app->app_id    = 3;
        $app->user_id   = 1;
        $app->is_active = 1;
        $app->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('app_support')) {
            Schema::drop('app_support');
        }
    }
}
