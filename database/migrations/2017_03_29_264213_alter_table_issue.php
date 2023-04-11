<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableIssue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('issue', function (Blueprint $table) {
            if ( Schema::hasColumn('issue', 'issue_status_id') == false ) {
                $table->integer('issue_status_id')->default(1);
                $table->foreign('issue_status_id')->references('id')->on('issue_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('issue')) {
            Schema::table('issue', function($table) {
                if ( Schema::hasColumn('issue', 'issue_status_id') == true ) {
                    $table->dropColumn('issue_status_id');
                }
            });
        }
    }
}
