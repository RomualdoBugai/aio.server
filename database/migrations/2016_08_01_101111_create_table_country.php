<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCountry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('code', 3);
            $table->string('name', 255);
            $table->integer('international_code')->default(55);
            $table->index('code');
            $table->index('name');
        });

        $seeder = new \App\Models\Internationalization\Country;
        $seeder->code = 'br';
        $seeder->name = 'brazil';
        $seeder->international_code = '55';
        $seeder->save();

        $seeder = new \App\Models\Internationalization\Country;
        $seeder->code = 'us';
        $seeder->name = 'united states';
        $seeder->international_code = '1';
        $seeder->save();

        $seeder = new \App\Models\Internationalization\Country;
        $seeder->code = 'ca';
        $seeder->name = 'canada';
        $seeder->international_code = '1';
        $seeder->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('country'))
        {
            Schema::drop('country');
        }
    }
}
