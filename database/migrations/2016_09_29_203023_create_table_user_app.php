<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_app', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->integer('app_id');
            $table->foreign('app_id')->references('id')->on('app');
            $table->timestamps();
        });

        # william
        $userApp = new \App\Models\UserApp;
        $userApp->user_id = 1;
        $userApp->app_id  = 1;
        $userApp->save();

        $userApp = new \App\Models\UserApp;
        $userApp->user_id = 1;
        $userApp->app_id  = 2;
        $userApp->save();

        $userApp = new \App\Models\UserApp;
        $userApp->user_id = 1;
        $userApp->app_id  = 3;
        $userApp->save();

        # suporte
        $userApp = new \App\Models\UserApp;
        $userApp->user_id = 2;
        $userApp->app_id  = 1;
        $userApp->save();

        $userApp = new \App\Models\UserApp;
        $userApp->user_id = 2;
        $userApp->app_id  = 2;
        $userApp->save();

        $userApp = new \App\Models\UserApp;
        $userApp->user_id = 2;
        $userApp->app_id  = 3;
        $userApp->save();

        # romualdo
        $userApp = new \App\Models\UserApp;
        $userApp->user_id = 3;
        $userApp->app_id  = 1;
        $userApp->save();

        $userApp = new \App\Models\UserApp;
        $userApp->user_id = 3;
        $userApp->app_id  = 2;
        $userApp->save();

        # paulo
        $userApp = new \App\Models\UserApp;
        $userApp->user_id = 4;
        $userApp->app_id  = 1;
        $userApp->save();

        $userApp = new \App\Models\UserApp;
        $userApp->user_id = 4;
        $userApp->app_id  = 2;
        $userApp->save();


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('user_app'))
        {
            Schema::drop('user_app');
        }
    }
}
