<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePaymentMethod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_method', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 24); 
            $table->boolean('is_active')->default(1);  
            $table->timestamps();
            $table->index('name');
        });

        $app = new \App\Models\PaymentMethod;
        $app->name        = "boleto";
        $app->save();

        $app = new \App\Models\PaymentMethod;
        $app->name        = "cartão de crédito";
        $app->save();

        $app = new \App\Models\PaymentMethod;
        $app->name        = "cartão de débito";
        $app->save();

        $app = new \App\Models\PaymentMethod;
        $app->name        = "depósito bacário";
        $app->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payment_method');
    }
}
