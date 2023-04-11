<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 96);
            $table->integer('user_limit');
            $table->integer('enterprise_limit');
            $table->integer('upload_limit');
            $table->boolean('send_file_email');
            $table->boolean('is_active');
            $table->boolean('allow_choose');
            $table->decimal('price', 10, 2)->default(19.90);
            $table->string('product_code', 32);
            $table->integer('app_id');
            $table->foreign('app_id')->references('id')->on('app');
            $table->timestamps();
        });

        $plan = new \App\Models\Plan;
        $plan->name             = 'plano 1';
        $plan->user_limit       = 1;
        $plan->enterprise_limit = 1;
        $plan->upload_limit     = 2;
        $plan->send_file_email  = true;
        $plan->is_active        = true;
        $plan->price            = 19.90;
        $plan->allow_choose     = true;
        $plan->product_code     = 12345678;
        $plan->app_id           = 1;
        $plan->save();

        $plan = new \App\Models\Plan;
        $plan->name             = 'plano 2';
        $plan->user_limit       = 2;
        $plan->enterprise_limit = 2;
        $plan->upload_limit     = 5;
        $plan->send_file_email  = true;
        $plan->is_active        = true;
        $plan->price            = 39.90;
        $plan->allow_choose     = true;
        $plan->product_code     = 23456789;
        $plan->app_id           = 1;
        $plan->save();

        $plan = new \App\Models\Plan;
        $plan->name             = 'plano 3';
        $plan->user_limit       = 3;
        $plan->enterprise_limit = 3;
        $plan->upload_limit     = 10;
        $plan->send_file_email  = true;
        $plan->is_active        = true;
        $plan->price            = 49.90;
        $plan->allow_choose     = true;
        $plan->product_code     = 34567890;
        $plan->app_id           = 1;
        $plan->save();

        $plan = new \App\Models\Plan;
        $plan->name             = 'sob consulta';
        $plan->user_limit       = 99999;
        $plan->enterprise_limit = 99999;
        $plan->upload_limit     = 99999;
        $plan->send_file_email  = true;
        $plan->is_active        = true;
        $plan->price            = 00.00;
        $plan->allow_choose     = false;
        $plan->product_code     = 45678901;
        $plan->app_id           = 1;
        $plan->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('plan'))
        {
            Schema::drop('plan');
        }
    }
}
