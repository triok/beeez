<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserRoleToProjectUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_users', function (Blueprint $table) {
            $table->enum('user_role', ['employer', 'follower'])->default('employer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_users', function (Blueprint $table) {
            $table->dropColumn('user_role');
        });
    }
}
