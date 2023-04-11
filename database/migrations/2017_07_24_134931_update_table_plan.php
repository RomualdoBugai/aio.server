<?php

use App\Models\Plan as Plan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTablePlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = array(
            'name'              => 'simples',
            'user_limit'        => 1,
            'enterprise_limit'  => 1,
            'upload_limit'      => 25,
            'price'             => 15.00,
            'product_code'      => 1,
            'app_id'            => 2,
        );

        Plan::where('id', 1)->update($data);

        $data = array(
            'name'              => 'avançado',
            'user_limit'        => 4,
            'enterprise_limit'  => 2,
            'upload_limit'      => 50,
            'price'             => 25.00,
            'product_code'      => 2,
            'app_id'            => 2,
        );

        Plan::where('id', 2)->update($data);

        $data = array(
            'name'              => 'exclusivo',
            'user_limit'        => 7,
            'enterprise_limit'  => 5,
            'upload_limit'      => 100,
            'price'             => 40.00,
            'product_code'      => 3,
            'app_id'            => 2,
        );

        Plan::where('id', 3)->update($data);

        $data = array(
            'name'              => 'simples',
            'user_limit'        => 1,
            'enterprise_limit'  => 1,
            'upload_limit'      => 100,
            'price'             => 20.00,
            'product_code'      => 4,
            'app_id'            => 1,
        );

        Plan::where('id', 4)->update($data);

        $plan = new \App\Models\Plan;
        $plan->name             = 'avançado';
        $plan->user_limit       = 4;
        $plan->enterprise_limit = 5;
        $plan->upload_limit     = 1000;
        $plan->send_file_email  = true;
        $plan->is_active        = true;
        $plan->price            = 40.00;
        $plan->allow_choose     = true;
        $plan->product_code     = 5;
        $plan->app_id           = 1;
        $plan->save();

        $plan = new \App\Models\Plan;
        $plan->name             = 'simples';
        $plan->user_limit       = 1;
        $plan->enterprise_limit = 1;
        $plan->upload_limit     = 25;
        $plan->send_file_email  = true;
        $plan->is_active        = true;
        $plan->price            = 15.00;
        $plan->allow_choose     = true;
        $plan->product_code     = 6;
        $plan->app_id           = 4;
        $plan->save();

        $plan = new \App\Models\Plan;
        $plan->name             = 'avançado';
        $plan->user_limit       = 4;
        $plan->enterprise_limit = 2;
        $plan->upload_limit     = 50;
        $plan->send_file_email  = true;
        $plan->is_active        = true;
        $plan->price            = 25.00;
        $plan->allow_choose     = true;
        $plan->product_code     = 7;
        $plan->app_id           = 4;
        $plan->save();

        $plan = new \App\Models\Plan;
        $plan->name             = 'exclusivo';
        $plan->user_limit       = 7;
        $plan->enterprise_limit = 5;
        $plan->upload_limit     = 100;
        $plan->send_file_email  = true;
        $plan->is_active        = true;
        $plan->price            = 40.00;
        $plan->allow_choose     = true;
        $plan->product_code     = 8;
        $plan->app_id           = 4;
        $plan->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('plan');
    }
}
