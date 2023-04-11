<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBudgetStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 224);
            $table->string('description', 224);
            $table->timestamps();
        });

        $app = new \App\Models\BudgetStatus;
        $app->name          = "created";
        $app->description   = "budget created";
        $app->save();

        $app = new \App\Models\BudgetStatus;
        $app->name          = "reply";
        $app->description   = "budget reply";
        $app->save();

        $app = new \App\Models\BudgetStatus;
        $app->name          = "accept";
        $app->description   = "plan accept client";
        $app->save();

        $app = new \App\Models\BudgetStatus;
        $app->name          = "denied";
        $app->description   = "plan denied client";
        $app->save();
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('budget_status'))
        {
            Schema::drop('budget_status');
        }
    }
}
