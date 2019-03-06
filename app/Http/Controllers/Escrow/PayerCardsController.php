<?php

namespace App\Http\Controllers\Escrow;

use App\Http\Controllers\Controller;
use App\Http\Requests\PayerCardConfirmRequest;
use App\Services\WalletOneService;
use Illuminate\Support\Facades\Auth;

class PayerCardsController extends Controller
{
    public function store(PayerCardConfirmRequest $request)
    {
        if (!WalletOneService::validateSignature($request->get('Signature'), $request->except(['Signature']))) {
            flash()->error('Возникла ошибка при проверке ответа от сервера! Добавте карту еще раз.');

            return redirect(route('account') . '#bill');
        }

        Auth::user()->addPayerCard($request->only(['PlatformPayerId', 'PayerPaymentToolId']));

        flash()->success('Карта для оплаты работы успешно добавлена.');

        return redirect(route('account') . '#bill');
    }

    public function destroy()
    {
        $payerCard = Auth::user()->payerCard();

        if (!$payerCard) {
            flash()->error('Карта не найдена!');

            return redirect(route('account') . '#bill');
        }

        $payerCard->delete();

        flash()->success('Карта для оплаты работы успешно удалена.');

        return redirect(route('account') . '#bill');
    }
}
