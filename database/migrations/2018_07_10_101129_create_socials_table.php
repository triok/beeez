<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('social_user', function (Blueprint $table) {
            $table->integer('social_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();

            $table->string('link');
            $table->string('status');
        });

        Schema::table('social_user', function($table) {
            $table->foreign('social_id')->references('id')->on('socials')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->primary(['social_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_user');
        Schema::dropIfExists('socials');
    }
}
