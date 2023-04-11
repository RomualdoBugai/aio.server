<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertTableApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $app = new \App\Models\App;
        $app->name          = "lognfe";
        $app->full_name     = "nota fiscal eletrônica de produto";
        $app->description   = "nota fiscal eletrônica de produto";
        $app->author        = "Romualdo Bugai";
        $app->resume        = json_encode(['en' => 'nfe system description', 'pt' => 'descrição do sistema nfe']);
        $app->have_plan     = true;
        $app->url           = 'http://www.aplicativosmpa.com/lognfe';
        $app->save();

        $app = new \App\Models\App;
        $app->name          = "logadm";
        $app->full_name     = "adiministrador dos logs";
        $app->description   = "adiministrador dos logs";
        $app->author        = "Romualdo Bugai";
        $app->resume        = json_encode(['en' => 'adm system description', 'pt' => 'descrição do sistema adm']);
        $app->have_plan     = true;
        $app->url           = 'http://www.aplicativosmpa.com/logadm';
        $app->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
