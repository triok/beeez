<?php

namespace App\Listeners;

use App\Events\ProposalApplied;
use App\Services\WalletOneService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterWalletOneDeal
{
    /**
     * Handle the event.
     *
     * @param ProposalApplied $event
     * @return void
     */
    public function handle(ProposalApplied $event)
    {
        $application = $event->application;

        $payer = $application->job->user;

        $beneficiary = $application->user;

        $deal = $application->deals()->create([
            'payer_id' => $payer->id,
            'payer_tool_id' => $payer->payerCard()->payer_payment_tool_id,
            'beneficiary_id' => $beneficiary->id,
            'beneficiary_tool_id' => $beneficiary->beneficiaryCard()->beneficiary_payment_tool_id,
            'state' => 'new',
            'amount' => $application->job_price,
            'currency_id' => 643,
            'short_description' => 'Оплата сделки',
            'deal_type' => 'deferred',
        ]);

        try {
            $response = WalletOneService::registerDeal($deal);

            $deal->update([
                'state' => 'created',
                'expire_at' => date('Y-m-d H:i:s', strtotime($response->ExpireDate)),
            ]);
        } catch (\Exception $e) {
            //
        }
    }
}
