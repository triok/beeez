<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewsTable extends Migration
{

    public function up()
    {
        Schema::create('views', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->morphs('viewable');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('views');
    }
}
