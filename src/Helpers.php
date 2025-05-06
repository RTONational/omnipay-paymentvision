<?php

namespace Omnipay\PaymentVision;

class Helpers
{
    public static function stripDigits(?string $value): ?string
    {
        return null === $value
            ? null
            : preg_replace('/\D/', '', $value);
    }
}
