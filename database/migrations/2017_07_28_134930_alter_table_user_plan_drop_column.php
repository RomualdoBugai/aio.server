<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUserPlanDropColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_plan', function (Blueprint $table) {
            $table->dropColumn('started_at');
            $table->dropColumn('end_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_plan', function (Blueprint $table) {
            $table->date('started_at');
            $table->date('end_at');
        });
    }
}
