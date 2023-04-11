<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64);
            $table->string('code', 3);
            $table->string('format');
            $table->timestamps();
            $table->index('name');
            $table->index('code');
        });

        $currency          = new \App\Models\Currency;
        $currency->name    = 'brazilian real';
        $currency->code    = 'BRL';
        $currency->format  = '2+,+.+';
        $currency->save();

        $currency          = new \App\Models\Currency;
        $currency->name    = 'american dollar';
        $currency->code    = 'USD';
        $currency->format  = '2+.+,+';
        $currency->save();

        $currency          = new \App\Models\Currency;
        $currency->name    = 'euro';
        $currency->code    = 'EUR';
        $currency->format  = '2+,+.+';
        $currency->save();

        $currency          = new \App\Models\Currency;
        $currency->name    = 'libra';
        $currency->code    = 'GBP';
        $currency->format  = '2+.+,+';
        $currency->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('currency')) {
            Schema::drop('currency');
        }
    }
}
