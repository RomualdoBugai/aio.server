<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrderStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 224);
            $table->string('description', 224);
            $table->timestamps();
        });

        $app = new \App\Models\Payment\OrderStatus;
        $app->name          = "completed";
        $app->description   = "payment completed";
        $app->save();

        $app = new \App\Models\Payment\OrderStatus;
        $app->name          = "pending";
        $app->description   = "waiting payment";
        $app->save();

        $app = new \App\Models\Payment\OrderStatus;
        $app->name          = "canceled";
        $app->description   = "payment canceled";
        $app->save();

        $app = new \App\Models\Payment\OrderStatus;
        $app->name          = "error";
        $app->description   = "payment with error";
        $app->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_status');
    }
}
