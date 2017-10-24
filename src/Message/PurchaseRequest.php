<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractRequest;

class PurchaseRequest extends AbstractRequest
{
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

        $data = array();

        $data['authentication'] = $this->getAuthenticationParams();

        $data['creditCardAccount'] = [
            'CreditCardNumber' => $card->getNumber(),
            'CreditCardExpirationMonth' => $card->getExpirationMonth(),
            'CreditCardExpirationYear' => $card->getExpirationYear(),
            'CVVCode' => $card->getCvv(),
            'CardType' => $card->getBrand(),
        ];

        $data['creditCardPayment'] = array(
            'MerchantPayeeCode' => $this->getMerchantPayeeCode(), // set in params
            'Amount' => $this->getAmount(),
            'Comment' => $this->getComment(), // Rent-To-Own Deposit
            'UserDefinedOne' => $this->getUserDefinedOne(), // $this->data['Customer']['name'],
            'HoldForApproval' => $this->getHoldForApproval(), // false
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
    public function initialize(array $parameters = array())
    {

    }

    /**
     * Get all request parameters
     *
     * @return array
     */
    public function getParameters()
    {

    }

    /**
     * Get the response to this request (if the request has been sent)
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {

    }

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
            $this->soap = new SoapClient($this->wsdl);
        }
        
        $response = call_user_func_array(array($this->soap, 'MakeCreditCardPayment'), array($data));

        return $this->response = new Response($this, $response);
    }
}
