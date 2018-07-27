<?php

namespace Monte;

use Monte\Resources\Api;

class Payments extends Api
{

    public static function isStripeConnected()
    {
        return self::request('/payments/is-stripe-connected', [], [], false, false);
    }

    public static function isStripeLive()
    {
        return self::request('/payments/is-stripe-live', [], [], false, false);
    }

    public static function createCardCustomer($cardholderName, $cardholderEmail, $cardToken)
    {
        return self::request('/payments/create-card-customer', [], [
            'cardholderName' => $cardholderName,
            'cardholderEmail' => $cardholderEmail,
            'cardToken' => $cardToken,
        ], false, true);
    }

    public static function createCardToken($cardNumber, $expMonth, $expYear, $cvc)
    {
        return self::request('/payments/create-card-token', [], [
            'cardNumber' => $cardNumber,
            'expMonth' => $expMonth,
            'expYear' => $expYear,
            'cvc' => $cvc
        ], false, true);
    }

    public static function createCardCharge($amount, $currency, $customerId, $description)
    {
        return self::request('/payments/create-card-charge', [], [
            'amount' => $amount,
            'currency' => $currency,
            'customerId' => $customerId,
            'description' => $description,
        ], false, true);
    }

    public static function updateCardDefault($customerId, $cardToken)
    {
        return self::request('/payments/update-card-default', [], [
            'customerId' => $customerId,
            'cardToken' => $cardToken
        ], false, true);
    }

}