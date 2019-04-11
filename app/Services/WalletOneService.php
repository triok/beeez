<?php

namespace App\Services;

use App\Models\Escrow\Deal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class WalletOneService
{
    /**
     * Get payer api url.
     *
     * @return string
     */
    public static function payerAction()
    {
        return self::baseAction() . 'v2/payer';
    }

    /**
     * Get payer api url.
     *
     * @return string
     */
    public static function beneficiaryAction()
    {
        return self::baseAction() . 'v2/beneficiary';
    }

    /**
     * Get payer fields.
     *
     * @return array
     */
    public static function payerFields()
    {
        $fields = [
            'PlatformId' => config('wallet-one.platform_id'),
            'PlatformPayerId' => Auth::id(),
            'PhoneNumber' => Auth::user()->phone,
            'ReturnUrl' => route('escrow-payer-card'),
            'Timestamp' => Carbon::now('UTC')->format('Y-m-d\TH:i:s'),
        ];

        $fields['Signature'] = self::getSignature($fields);

        return $fields;
    }

    /**
     * Get payer fields.
     *
     * @return array
     */
    public static function beneficiaryFields()
    {
        $fields = [
            'PlatformId' => config('wallet-one.platform_id'),
            'PlatformBeneficiaryId' => Auth::id(),
            'PhoneNumber' => Auth::user()->phone,
            'ReturnUrl' => route('escrow-beneficiary-card'),
            'Timestamp' => Carbon::now('UTC')->format('Y-m-d\TH:i:s'),
        ];

        $fields['Signature'] = self::getSignature($fields);

        return $fields;
    }

    /**
     * Validate response signature.
     *
     * @param string $signature
     * @param array $data
     * @return bool
     */
    public static function validateSignature($signature, $data)
    {
        return $signature == self::getSignature($data);
    }

    /**
     * Validate response signature.
     *
     * @param Deal $deal
     * @return bool
     */
    public static function registerDeal(Deal $deal)
    {
        $fields = [
            'PlatformDealId' => $deal->id,
            'PlatformPayerId' => $deal->payer_id,
            'PayerPhoneNumber' => auth()->user()->phone,
            'PayerPaymentToolId' => $deal->payer_tool_id,
            'PlatformBeneficiaryId' => $deal->beneficiary_id,
            'BeneficiaryPaymentToolId' => $deal->beneficiary_tool_id,
            'Amount' => $deal->amount,
            'CurrencyId' => $deal->currency_id,
            'ShortDescription' => $deal->short_description,
            'DeferPayout' => true,
        ];

        $body = json_encode($fields);

        $url = self::baseAction() . '/api/v3/deals';
        $timestamp = Carbon::now('UTC')->format('Y-m-d\TH:i:s');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($body),
            'X-Wallet-PlatformId: ' . config('wallet-one.platform_id'),
            'X-Wallet-Signature: ' . self::getApiSignature($url, $timestamp, $body),
            'X-Wallet-Timestamp: ' . $timestamp,
        ]);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        $output = curl_exec($ch);

        curl_close($ch);

        return json_decode($output);
    }

    /**
     * Validate response signature.
     *
     * @param Deal $deal
     * @return bool
     */
    public static function completeDeal(Deal $deal)
    {
        $body = json_encode([]);

        $url = self::baseAction() . '/api/v3/deals/' . $deal->id . '/complete';
        $timestamp = Carbon::now('UTC')->format('Y-m-d\TH:i:s');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($body),
            'X-Wallet-PlatformId: ' . config('wallet-one.platform_id'),
            'X-Wallet-Signature: ' . self::getApiSignature($url, $timestamp, $body),
            'X-Wallet-Timestamp: ' . $timestamp,
        ]);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        $output = curl_exec($ch);

        curl_close($ch);

        return json_decode($output);
    }

    /**
     * Validate response signature.
     *
     * @param Deal $deal
     * @return bool
     */
    public static function declineDeal(Deal $deal)
    {
        $body = json_encode([]);

        $url = self::baseAction() . '/api/v3/deals/' . $deal->id . '/cancel';
        $timestamp = Carbon::now('UTC')->format('Y-m-d\TH:i:s');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($body),
            'X-Wallet-PlatformId: ' . config('wallet-one.platform_id'),
            'X-Wallet-Signature: ' . self::getApiSignature($url, $timestamp, $body),
            'X-Wallet-Timestamp: ' . $timestamp,
        ]);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

        $output = curl_exec($ch);

        curl_close($ch);

        return json_decode($output);
    }

    /**
     * Get base api url.
     *
     * @return mixed
     */
    protected static function baseAction()
    {
        if (config('wallet-one.environment') == 'production') {
            return config('wallet-one.production_url');
        }

        return config('wallet-one.sandbox_url');
    }

    /**
     * Process signature from given POST data
     *
     * @param array $data
     * @return string
     */
    protected static function getSignature($data)
    {
        uksort($data, "strcasecmp");

        $fieldValues = "";

        foreach ($data as $value) {
            $fieldValues .= iconv("utf-8", "windows-1251", $value);
        }

        return base64_encode(mhash(MHASH_SHA256, $fieldValues . config('wallet-one.signature_key')));
    }

    /**
     * Process signature from given POST data
     *
     * @param $url
     * @param $timestamp
     * @param string $body
     * @return string
     */
    protected static function getApiSignature($url, $timestamp, $body)
    {
        return base64_encode(mhash(MHASH_SHA256, $url . $timestamp . $body . config('wallet-one.signature_key')));
    }
}