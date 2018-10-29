<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('organization_id');

            $table->string('name');

            $table->string('specialization')->nullable();
            $table->text('responsibilities')->nullable();
            $table->text('conditions')->nullable();
            $table->text('requirements')->nullable();

            $table->timestamp('published_at')->nullable();

            $table->integer('total_views')->default(0);
            $table->integer('total_responses')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancies');
    }
}
