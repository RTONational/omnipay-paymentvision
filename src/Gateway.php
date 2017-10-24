<?php

namespace Omnipay\PaymentVision;

use Omnipay\Common\AbstractGateway;

/**
 * PaymentVision Gateway Class
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'PaymentVision';
    }

    public function getDefaultParameters()
    {
        return array(
            'pvLogin' => '',
            'pvPassword' => '',
            'pvAPIKey' => '',
            'pvToken' => '',
            'merchantPayeeCode' => '',
            'liveWsdl' => 'https://portal.paymentvision.com/api/api.asmx?wsdl',
            'testWsdl' => 'https://pvdemo.autoscribe.com/API/API.asmx?wsdl',
            'testMode' => false
        );
    }

    public function getPvLogin()
    {
        return $this->getParameter('pvLogin');
    }

    public function setPvLogin($value)
    {
        return $this->setParameter('pvLogin', $value);
    }

    public function getPvPassword()
    {
        return $this->getParameter('pvPassword');
    }

    public function setPvPassword($value)
    {
        return $this->setParameter('pvPassword', $value);
    }

    public function getPvAPIKey()
    {
        return $this->getParameter('pvAPIKey');
    }

    public function setPvAPIKey($value)
    {
        return $this->setParameter('pvAPIKey', $value);
    }

    public function getPvToken()
    {
        return $this->getParameter('pvToken');
    }

    public function setPvToken($value)
    {
        return $this->setParameter('pvToken', $value);
    }

    public function getMerchantPayeeCode()
    {
        return $this->getParameter('merchantPayeeCode');
    }

    public function setMerchantPayeeCode($value)
    {
        return $this->setParameter('merchantPayeeCode', $value);
    }

    public function getLiveWsdl()
    {
        return $this->getParameter('liveWsdl');
    }

    public function setLiveWsdl($value)
    {
        return $this->setParameter('liveWsdl', $value);
    }

    public function getTestWsdl()
    {
        return $this->getParameter('testWsdl');
    }

    public function setTestWsdl($value)
    {
        return $this->setParameter('testWsdl', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\PurchaseRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\RefundRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\VoidRequest', $parameters);
    }
}
