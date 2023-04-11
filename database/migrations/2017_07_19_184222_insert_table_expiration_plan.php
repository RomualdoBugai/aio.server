<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\UserApp as UserApp;

class InsertTableExpirationPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = UserApp::get();

        foreach ($data as $value) {

            $app = new \App\Models\ExpirationPlan;
            $app->user_id           = $value['user_id'];           
            $app->app_id            = $value['app_id'];
            $app->start_date        = date('Y-m-d');
            $app->end_date          = date('Y-m-d', strtotime("+30 days"));
            
            if($value['app_id'] == '1' || $value['app_id'] == '2' || $value['app_id'] == '4'){
                $app->save();

            }# not save            
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('expiration_plan');
    }
}
