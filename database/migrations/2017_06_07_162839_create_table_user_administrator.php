<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserAdministrator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_administrator', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->integer('insert_user_id');
            $table->foreign('insert_user_id')->references('id')->on('user');            
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->index('user_id');
            $table->index('insert_user_id');
        });

        # Romualdo Bugai
        $userAdministrator = new \App\Models\UserAdministrator;
        $userAdministrator->user_id = 3;
        $userAdministrator->insert_user_id  = 1;
        $userAdministrator->save();

        # Marco Possiede
        /*$userAdministrator = new \App\Models\UserAdministrator;
        $userAdministrator->user_id = 6;
        $userAdministrator->insert_user_id  = 1;
        $userAdministrator->save();*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('user_administrator')) {
            Schema::drop('user_administrator');
        }
    }
}
