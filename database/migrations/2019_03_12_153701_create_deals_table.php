<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('application_id'); // PlatformDealId
            $table->unsignedInteger('payer_id'); // PlatformPayerId
            $table->unsignedInteger('payer_tool_id'); // PayerPaymentToolId
            $table->unsignedInteger('beneficiary_id'); // PlatformBeneficiaryId
            $table->unsignedInteger('beneficiary_tool_id'); // BeneficiaryPaymentToolId

            $table->enum('state', [
                'new',
                'created',
                'payment_processing',
                'payment_process_error',
                'paid',
                'payout_processing',
                'payout_process_error',
                'completed',
                'canceling',
                'cancel_error',
                'canceled',
                'payment_hold',
                'payment_hold_processing',
                'archived',
            ]); // DealStateId

            $table->dateTime('expire_at')->nullable(); // ExpireDate
            $table->decimal('amount'); // Amount
            $table->unsignedInteger('currency_id'); // CurrencyId
            $table->text('short_description'); // ShortDescription
            $table->text('full_description')->nullable(); // FullDescription
            $table->enum('deal_type', ['deferred', 'instant']); // DealTypeId

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
        Schema::dropIfExists('deals');
    }
}
