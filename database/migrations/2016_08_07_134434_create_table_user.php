<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 96);
            $table->string('email', 112)->unique();
            $table->string('password', 32);
            $table->timestamps();
            $table->index('email');
        });

        $user = new \App\Models\User;
        $user->name = "william novak";
        $user->email = "williamnvk@gmail.com";
        $user->password = md5("47851239");
        $user->save();

        $user = new \App\Models\User;
        $user->name = "suporte";
        $user->email = "mpainformatica@mpainformatica.com";
        $user->password = md5("123456");
        $user->save();

        $user = new \App\Models\User;
        $user->name = "romualdo bugai";
        $user->email = "romualdo.bugai@gmail.com";
        $user->password = md5("123456");
        $user->save();

        $user = new \App\Models\User;
        $user->name = "marco aurélio possiede";
        $user->email = "possiede@hotmail.com";
        $user->password = md5("coxa1985");
        $user->save();

        $user = new \App\Models\User;
        $user->name = "Sergio Amed Silva";
        $user->email = "sergio@lumbex.com";
        $user->password = md5("abc123");
        $user->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('user')) {
            Schema::drop('user');
        }
    }
}
