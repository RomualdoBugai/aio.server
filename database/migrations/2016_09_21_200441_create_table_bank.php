<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 48);
            $table->string('code', 5);
            $table->timestamps();
        });

        $bank = new \App\Models\Bank;
        $bank->name    = 'banco do brasil';
        $bank->code    = '001';
        $bank->save();

        $bank = new \App\Models\Bank;
        $bank->name    = 'bradesco';
        $bank->code    = '347';
        $bank->save();

        $bank = new \App\Models\Bank;
        $bank->name    = 'itaú';
        $bank->code    = '143';
        $bank->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('bank'))
        {
            Schema::drop('bank');
        }
    }
}
