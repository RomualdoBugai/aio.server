<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAttachment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('attachment', function (Blueprint $table) {


            if (Schema::hasColumn('attachment', 'filename')) {
                Schema::table('attachment', function (Blueprint $table){
                    $table->string('filename', 128)->change();
                });
            } else {
                $table->string('filename', 128)->nullable();
            }

            if (Schema::hasColumn('attachment', 'path')) {
                Schema::table('attachment', function (Blueprint $table){
                    $table->string('path', 255)->change();
                });
            } else {
                $table->string('path', 255)->nullable();
            }

            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('attachment', function (Blueprint $table) {
            if (Schema::hasColumn('attachment', 'path')) {
                $table->string('path', 48)->change();
            }
            if (Schema::hasColumn('attachment', 'filename')) {
                $table->string('filename', 48)->change();
            }
        });
    }
}
