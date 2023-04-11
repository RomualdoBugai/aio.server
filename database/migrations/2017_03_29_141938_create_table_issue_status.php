<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableIssueStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('issue_status', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name', '48');
        });


        $seeder = new \App\Models\Support\IssueStatus;
        $seeder->name = 'new';
        $seeder->save();
        # ---
        $seeder = new \App\Models\Support\IssueStatus;
        $seeder->name = 'pending';
        $seeder->save();
        # ---
        $seeder = new \App\Models\Support\IssueStatus;
        $seeder->name = 'zin-progress';
        $seeder->save();
        # ---
        $seeder = new \App\Models\Support\IssueStatus;
        $seeder->name = 'closed';
        $seeder->save();

        $seeder = new \App\Models\Support\IssueStatus;
        $seeder->name = 'feedback';
        $seeder->save();
        # ---
        $seeder = new \App\Models\Support\IssueStatus;
        $seeder->name = 'closed-without-service';
        $seeder->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        if (Schema::hasTable('issue_status')) {
            Schema::drop('issue_status');
        }
    }
}
