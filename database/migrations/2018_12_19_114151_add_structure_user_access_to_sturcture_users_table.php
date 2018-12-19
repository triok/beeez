<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStructureUserAccessToSturctureUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('structure_users', function (Blueprint $table) {
            $table->boolean('can_add_user')->default(false)->nullable();
            $table->boolean('can_add_project')->default(false)->nullable();
            $table->boolean('can_add_job')->default(false)->nullable();
            $table->boolean('can_see_all_projects')->default(false)->nullable();
            $table->boolean('can_add_user_to_project')->default(false)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('structure_users', function (Blueprint $table) {
            $table->dropColumn('can_add_user');
            $table->dropColumn('can_add_project');
            $table->dropColumn('can_add_job');
            $table->dropColumn('can_see_all_projects');
            $table->dropColumn('can_add_user_to_project');
        });
    }
}
