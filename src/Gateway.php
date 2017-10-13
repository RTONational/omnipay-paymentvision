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
            'PvLogin' => '',
            'PvPassword' => '',
            'PvAPIKey' => '',
            'PvToken' => '',
        );
    }

    public function getPvLogin()
    {
        return $this->getParameter('PvLogin');
    }

    public function setPvLogin($value)
    {
        return $this->setParameter('PvLogin', $value);
    }

    public function getPvPassword()
    {
        return $this->getParameter('PvPassword');
    }

    public function setPvPassword($value)
    {
        return $this->setParameter('PvPassword', $value);
    }

    public function getPvAPIKey()
    {
        return $this->getParameter('PvAPIKey');
    }

    public function setPvAPIKey($value)
    {
        return $this->setParameter('PvAPIKey', $value);
    }

    public function getPvToken()
    {
        return $this->getParameter('PvToken');
    }

    public function setPvToken($value)
    {
        return $this->setParameter('PvToken', $value);
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
