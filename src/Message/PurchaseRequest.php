<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\PaymentVision\CreditCardHelper;

class PurchaseRequest extends AbstractRequest
{
    /**
     * SoapClient Class
     */
    private $soap = null;

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

    public function getComment()
    {
        return $this->getParameter('comment');
    }

    public function setComment($value)
    {
        return $this->setParameter('comment', $value);
    }

    public function getUserDefinedOne()
    {
        return $this->getParameter('userDefinedOne');
    }

    public function setUserDefinedOne($value)
    {
        return $this->setParameter('userDefinedOne', $value);
    }

    public function getHoldForApproval()
    {
        return $this->getParameter('holdForApproval');
    }

    public function setHoldForApproval($value)
    {
        return $this->setParameter('holdForApproval', $value);
    }

    public function getIsRecurring()
    {
        return $this->getParameter('isRecurring');
    }

    public function setIsRecurring($value)
    {
        return $this->setParameter('isRecurring', $value);
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
        $this->validate('amount', 'card');
        /** @var CreditCard $card */
        $card = $this->getCard();
        $card->validate();

        $data = array();

        $data['authentication'] = $this->getAuthenticationParams();

        $data['creditCardAccount'] = array(
            'CreditCardNumber' => $card->getNumber(),
            'CreditCardExpirationMonth' => CreditCardHelper::formatExpiryMonth($card->getExpiryMonth()),
            'CreditCardExpirationYear' => $card->getExpiryYear(),
            'CVVCode' => $card->getCvv(),
            'CardType' => CreditCardHelper::paymentVisionCardType($card->getBrand()),
        );

        $data['creditCardPayment'] = array(
            'MerchantPayeeCode' => $this->getMerchantPayeeCode(),
            'Amount' => $this->getAmount(),
            'Comment' => $this->getComment(),
            'UserDefinedOne' => $this->getUserDefinedOne(),
            'HoldForApproval' => $this->getHoldForApproval(),
            'IsRecurring' => $this->getIsRecurring(),
        );
        
        $data['customer'] = array(
            'FirstName' => $card->getFirstName(),
            'LastName' => $card->getLastName(),
            'AdressLineOne' => $card->getBillingAddress1(),
            'City' => $card->getBillingCity(),
            'State' => $card->getBillingState(),
            'Zip' => substr($card->getBillingPostcode(), 0, 5),
        );

        return $data;
    }

    /**
     * Get authentication strings
     *
     * @return array
     */
    private function getAuthenticationParams()
    {
        return array(
            'PvLogin' => $this->getPvLogin(),
            'PvPassword' => $this->getPvPassword(),
            'PvAPIKey' => $this->getPvAPIKey(),
            'PvToken' => $this->getPvToken(),
        );
    }

    /**
     * Initialize request with parameters
     * @param array $parameters The parameters to send
     */
    // public function initialize(array $parameters = array())
    // {

    // }

    /**
     * Get all request parameters
     *
     * @return array
     */
    // public function getParameters()
    // {

    // }

    /**
     * Get the response to this request (if the request has been sent)
     *
     * @return ResponseInterface
     */
    // public function getResponse()
    // {

    // }

    /**
     * Send the request
     *
     * @return ResponseInterface
     */
    // public function send()
    // {
            // called in AbstractRequest
    // }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        if (!$this->soap) {
            $this->soap = new \SoapClient($this->getWsdl());
        }
        
        $response = call_user_func_array(array($this->soap, 'MakeCreditCardPayment'), array($data));

        return $this->response = new Response($this, $response);
    }
}
