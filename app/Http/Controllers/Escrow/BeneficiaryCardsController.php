<?php

namespace App\Http\Controllers\Escrow;

use App\Http\Controllers\Controller;
use App\Http\Requests\BeneficiaryCardConfirmRequest;
use App\Services\WalletOneService;
use Illuminate\Support\Facades\Auth;

class BeneficiaryCardsController extends Controller
{
    public function store(BeneficiaryCardConfirmRequest $request)
    {
        if (!WalletOneService::validateSignature($request->get('Signature'), $request->except(['Signature']))) {
            flash()->error('Возникла ошибка при проверке ответа от сервера! Добавте карту еще раз.');

            return redirect(route('account') . '#bill');
        }

        Auth::user()->addBeneficiaryCard($request->only(['PlatformBeneficiaryId', 'BeneficiaryPaymentToolId']));

        flash()->success('Карта для получения платежей успешно добавлена.');

        return redirect(route('account') . '#bill');
    }

    public function destroy()
    {
        $beneficiaryCard = Auth::user()->beneficiaryCard();

        if (!$beneficiaryCard) {
            flash()->error('Карта не найдена!');

            return redirect(route('account') . '#bill');
        }

        $beneficiaryCard->delete();

        flash()->success('Карта для оплаты работы успешно удалена.');

        return redirect(route('account') . '#bill');
    }
}
