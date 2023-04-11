<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('app', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 24);
            $table->string('full_name', 112);
            $table->string('description', 112);
            $table->string('author', 112);
            $table->boolean('have_plan')->default(false);
            $table->json('resume');
            $table->string('url', 224);
            $table->timestamps();
            $table->index('name');
        });

        $app = new \App\Models\App;
        $app->name        = "logfiscal";
        $app->full_name   = "logistica fiscal";
        $app->description = "logística fiscal";
        $app->author      = "Romualdo Bugai, Walter, Paulo Igor";
        $app->have_plan   = true;
        $app->resume      = json_encode(['en' => 'vault system description', 'pt' => 'descrição do sistema cofre']);
        $app->url         = 'http://www.aplicativosmpa.com/logfiscal';
        $app->save();

        $app = new \App\Models\App;
        $app->name          = "lognfse";
        $app->full_name     = "nota fiscal eletrônica de serviço";
        $app->description   = "nota fiscal eletrônica de serviço";
        $app->author        = "Romualdo Bugai";
        $app->resume        = json_encode(['en' => 'nfse system description', 'pt' => 'descrição do sistema nfse']);
        $app->have_plan     = true;
        $app->url           = 'http://www.aplicativosmpa.com/lognfse';
        $app->save();

        $app = new \App\Models\App;
        $app->name          = "lumbex";
        $app->full_name     = "lumbex";
        $app->description   = "lumbex";
        $app->author        = "William Novak";
        $app->resume        = json_encode(['en' => 'lumbex system description', 'pt' => 'descrição do sistema lumbex']);
        $app->have_plan     = false;
        $app->url           = 'http://www.aplicativosmpa.com/dev/lumbex/app/public/index.php/en/logIn';
        $app->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('app')) {
            Schema::drop('app');
        }
    }
}
