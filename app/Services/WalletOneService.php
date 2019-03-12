<?php

namespace App\Services;

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
}