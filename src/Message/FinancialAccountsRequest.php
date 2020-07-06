<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Send a request for details of a single transaction specified by transaction reference code
 */
class FinancialAccountsRequest extends AbstractRequest
{
    /**
     * SoapClient Class
     */
    private $soap = null;

    public function getSoap()
    {
        return $this->soap;
    }

    public function getSessionId()
    {
        return $this->getParameter('sessionId');
    }

    public function setSessionId($value)
    {
        return $this->setParameter('sessionId', $value);
    }

    public function getCustomerReferenceCode()
    {
        return $this->getParameter('customerReferenceCode');
    }

    public function setCustomerReferenceCode($value)
    {
        return $this->setParameter('customerReferenceCode', $value);
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

    public function getWsdl()
    {
        return $this->getTestMode() ? $this->getTestWsdl() : $this->getLiveWsdl();
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('sessionId', 'customerReferenceCode');

        $data = array();

        $data['sessionID'] = $this->getSessionId();

        $data['customerReferenceCode'] = $this->getCustomerReferenceCode();

        // set filter params
        $data['financialAccountTokenFilter']['FinancialAccountTokenTypes'] = null;
        $data['financialAccountTokenFilter']['FinancialAccountStatusTypes'] = 'Active';

        return $data;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        if (!$this->soap) {
            $this->soap = new \SoapClient($this->getWsdl(), array('trace' => $this->getTestMode()));
        }
        
        $response = call_user_func_array(array($this->soap, 'GetFinancialAccountTokens'), array($data));

        return $this->response = new Response($this, $response);
    }
}
