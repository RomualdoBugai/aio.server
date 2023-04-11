<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFollowUpReason extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow_up_reason', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name', 255);
        });

        $seeder = new \App\Models\FollowUp\FollowUpReason;
        $seeder->name = 'undefined';
        $seeder->save();

        $seeder = new \App\Models\FollowUp\FollowUpReason;
        $seeder->name = 'relationship';
        $seeder->save();

        $seeder = new \App\Models\FollowUp\FollowUpReason;
        $seeder->name = 'registry update';
        $seeder->save();

        $seeder = new \App\Models\FollowUp\FollowUpReason;
        $seeder->name = 'annotation';
        $seeder->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('follow_up_reason'))
        {
            Schema::drop('follow_up_reason');
        }
    }
}
