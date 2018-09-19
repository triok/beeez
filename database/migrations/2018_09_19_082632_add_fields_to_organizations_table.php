<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            // Форма собственности
            $table->string('ownership')->nullable();
            // ОГРН
            $table->string('ohrn')->nullable();
            // ИНН
            $table->string('inn')->nullable();
            // КПП
            $table->string('kpp')->nullable();
            // Юридиеский адрес
            $table->string('address')->nullable();
            // Банк
            $table->string('bank')->nullable();
            // БИК
            $table->string('bik')->nullable();
            // Расчетный счет
            $table->string('bank_account')->nullable();
            // Корреспондентский счет
            $table->string('correspondent_account')->nullable();
            // Контактное лицо
            $table->string('contact_person')->nullable();
            // Ваш e-mail
            $table->string('email')->nullable();
            // Ваш телефон
            $table->string('phone')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('ownership');
            $table->dropColumn('ohrn');
            $table->dropColumn('inn');
            $table->dropColumn('kpp');
            $table->dropColumn('address');
            $table->dropColumn('bank');
            $table->dropColumn('bik');
            $table->dropColumn('bank_account');
            $table->dropColumn('correspondent_account');
            $table->dropColumn('contact_person');
            $table->dropColumn('email');
            $table->dropColumn('phone');
        });
    }
}
