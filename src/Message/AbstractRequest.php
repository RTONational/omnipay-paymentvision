<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\PaymentVision\CommonParametersTrait;

abstract class AbstractRequest extends BaseAbstractRequest
{
    use CommonParametersTrait;

    /**
     * @var \SoapClient
     */
    protected $soap = null;

    public function getSoap()
    {
        return $this->soap;
    }
}
