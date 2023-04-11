<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableIssueType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issue_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 224);
            $table->timestamps();
        });

        $api = new \App\Models\Support\IssueType;
        $api->name = 'undefined';
        $api->save();

        $api = new \App\Models\Support\IssueType;
        $api->name = 'bug';
        $api->save();

        $api = new \App\Models\Support\IssueType;
        $api->name = 'improvement';
        $api->save();

        $api = new \App\Models\Support\IssueType;
        $api->name = 'infrastructure';
        $api->save();

        $api = new \App\Models\Support\IssueType;
        $api->name = 'implantation';
        $api->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('issue_type'))
        {
            Schema::drop('issue_type');
        }
    }
}
