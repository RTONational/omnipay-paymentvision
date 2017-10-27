<?php

namespace Omnipay\PaymentVision;

/**
 * CreditCardHelper class
 *
 * This class defines various static utility functions that are in use
 * throughout the Omnipay system.
 */
class CreditCardHelper
{
    const BRAND_VISA = 'Visa';
    const BRAND_MASTERCARD = 'MasterCard';
    const BRAND_DISCOVER = 'Discover';
    const BRAND_AMEX = 'AmericanExpress';
    const BRAND_DINERS_CLUB = 'DinersClub';
    const BRAND_UNKNOWN = 'Unknown';

    /**
     * Array mapping card brand name returned from CreditCard->getBrand() to
     * the corresponding name that PaymentVision accepts.
     */
    protected $supportedCards = [
        'visa' => self::BRAND_VISA,
        'mastercard' => self::BRAND_MASTERCARD,
        'discover' => self::BRAND_DISCOVER,
        'amex' => self::BRAND_AMEX,
        'diners_club' => self::BRAND_DINERS_CLUB
    ];

    /**
     * Return the value for the CardType property of creditCardAccount object
     *
     * @param  string  $creditCardBrand The brand name returned from CreditCard->getBrand()
     * @return string The card type name accepted by PaymentVision
     */
    public static function paymentVisionCardType($creditCardBrand)
    {
        if (isset($supportedCards[$creditCardBrand])) {
            return $supportedCards[$creditCardBrand];
        }

        return self::BRAND_UNKNOWN;
    }
}
