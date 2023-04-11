<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAppSmtp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('app_smtp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('host', 112);
            $table->string('username', 112);
            $table->string('password', 112);
            $table->string('port', 4);
            $table->string('encryption', 4);
            $table->integer('app_id');
            $table->foreign('app_id')->references('id')->on('app');
            $table->timestamps();
        });

        $app                = new \App\Models\AppSmtp;
        $app->host          = "smtp.uni5.net";
        $app->username      = "mpainformatica@mpainformatica.com";
        $app->password      = "mp@1nf0";
        $app->port          = 587;
        $app->encryption    = 'tls';
        $app->app_id        = 1;
        $app->save();

        $app                = new \App\Models\AppSmtp;
        $app->host          = "smtp.uni5.net";
        $app->username      = "mpainformatica@mpainformatica.com";
        $app->password      = "mp@1nf0";
        $app->port          = 587;
        $app->encryption    = 'tls';
        $app->app_id        = 2;
        $app->save();

        $app                = new \App\Models\AppSmtp;
        $app->host          = "smtp.uni5.net";
        $app->username      = "mpainformatica@mpainformatica.com";
        $app->password      = "mp@1nf0";
        $app->port          = 587;
        $app->encryption    = 'tls';
        $app->app_id        = 3;
        $app->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('app_smtp')) {
            Schema::drop('app_smtp');
        }
    }
}
