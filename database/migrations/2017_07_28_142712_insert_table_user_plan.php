<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\UserApp as UserApp;

class InsertTableUserPlan extends Migration
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

            $save = true;

            $app = new \App\Models\UserPlan;
            $app->user_id           = $value['user_id'];
            $app->app_id            = $value['app_id'];

            # Check the app and enter correct id
            switch ($value['app_id']) {
                case '1':

                    $app->plan_id           = 4;

                    break;

                case '2':

                    $app->plan_id           = 3;

                    break;

                case '4':

                    $app->plan_id           = 3;

                    break;
                
                default:
                    
                    $save = false;
                    
                    break;
            }
                       
            if($save == true){
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
        if (Schema::hasTable('user_plan'))
        {
            Schema::drop('user_plan');
        }
    }
}
