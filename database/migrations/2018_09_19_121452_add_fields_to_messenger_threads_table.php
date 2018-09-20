<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToMessengerThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messenger_threads', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->string('thread_type')->default('single');
            $table->string('avatar')->nullable();
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messenger_threads', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('thread_type');
            $table->dropColumn('avatar');
            $table->dropColumn('description');
        });
    }
}
