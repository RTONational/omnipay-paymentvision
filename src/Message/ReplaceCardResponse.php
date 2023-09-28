<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\PaymentVision\Message\Response as MessageResponse;

class ReplaceCardResponse extends MessageResponse
{
    /**
     * Gateway Reference Id
     *
     * @return null|string A reference id provided by PaymentVision to represent an account
     */
    public function getReferenceId()
    {
        return $this->data['ReplaceCreditCardAccountResult']['CreditCardAccountToken']['ReferenceID'] ?? null;
    }
}
