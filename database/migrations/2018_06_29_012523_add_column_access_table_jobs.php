<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAccessTableJobs extends Migration
{

    public function up()
    {
        Schema::table('jobs', function($table) {
            $table->string('access')->nullable()->after('instructions');
        });
    }

    public function down()
    {
        Schema::table('jobs', function($table) {
            $table->dropColumn('access');
        });
    }
}
