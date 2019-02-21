<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsApprovedToUserExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_experiences', function (Blueprint $table) {
            $table->enum('approved', ['not_approved', 'waiting', 'approved'])
                ->default('not_approved')
                ->after('position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_experiences', function (Blueprint $table) {
            $table->dropColumn('approved');
        });
    }
}
