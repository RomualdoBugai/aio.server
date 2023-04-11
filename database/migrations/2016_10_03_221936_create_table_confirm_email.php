<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableConfirmEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('confirm_email', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('user');
            $table->string('email', 112);
            $table->boolean('is_confirmed')->default(0);
            $table->string('verify', 32)->default('out-version');
            $table->timestamps();
        });

        $confirm = new App\Models\ConfirmEmail;
        $confirm->user_id = 1;
        $confirm->email = 'williamnvk@gmail.com';
        $confirm->is_confirmed = 1;
        $confirm->save();

        $confirm = new App\Models\ConfirmEmail;
        $confirm->user_id = 2;
        $confirm->email = 'mpainformatica@mpainformatica.com';
        $confirm->is_confirmed = 1;
        $confirm->save();

        $confirm = new App\Models\ConfirmEmail;
        $confirm->user_id = 3;
        $confirm->email = 'romualdo.bugai@gmail.com';
        $confirm->is_confirmed = 1;
        $confirm->save();

        $confirm = new App\Models\ConfirmEmail;
        $confirm->user_id = 4;
        $confirm->email = 'possiede@hotmail.com';
        $confirm->is_confirmed = 1;
        $confirm->save();

        $confirm = new App\Models\ConfirmEmail;
        $confirm->user_id = 5;
        $confirm->email = 'sergio@lumbex.com';
        $confirm->is_confirmed = 1;
        $confirm->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('confirm_email'))
        {
            Schema::drop('confirm_email');
        }
    }
}
