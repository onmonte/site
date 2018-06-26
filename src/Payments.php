<?php

namespace Monte;

use Monte\Resources\Api;

class Payments extends Api
{

    public static function createCardCustomer($cardholderName, $cardholderEmail, $cardToken)
    {
        return self::request('post', '/payments/create-card-customer', [], [
            'cardholderName' => $cardholderName,
            'cardholderEmail' => $cardholderEmail,
            'cardToken' => $cardToken,
        ]);
    }

    public static function createCardToken($cardNumber, $expMonth, $expYear, $cvc)
    {
        return self::request('post', '/payments/create-card-token', [], [
            'cardNumber' => $cardNumber,
            'expMonth' => $expMonth,
            'expYear' => $expYear,
            'cvc' => $cvc
        ]);
    }

    public static function createCardCharge($amount, $currency, $customerId, $description)
    {
        return self::request('post', '/payments/create-card-charge', [], [
            'amount' => $amount,
            'currency' => $currency,
            'customerId' => $customerId,
            'description' => $description,
        ]);
    }

    public static function updateCardDefault($customerId, $cardToken)
    {
        return self::request('post', '/payments/update-card-default', [], [
            'customerId' => $customerId,
            'cardToken' => $cardToken
        ]);
    }

}