<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractRequest;

class UpdateCardRequest extends AbstractRequest
{
    /**
     * SoapClient Class
     */
    private $soap = null;

    public function getSessionId()
    {
        return $this->getParameter('sessionId');
    }

    public function setSessionId($value)
    {
        return $this->setParameter('sessionId', $value);
    }

    public function getReferenceId()
    {
        return $this->getParameter('referenceId');
    }

    public function setReferenceId($value)
    {
        return $this->setParameter('referenceId', $value);
    }

    public function getNameOnCard()
    {
        return $this->getParameter('nameOnCard');
    }

    public function setNameOnCard($value)
    {
        return $this->setParameter('nameOnCard', $value);
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
        $this->validate('sessionId', 'referenceId', 'card', 'nameOnCard', 'customerReferenceCode');
        /** @var CreditCard $card */
        $card = $this->getCard();

        $data = array();

        $data['sessionID'] = $this->getSessionId();

        $data['referenceID'] = $this->getReferenceId();

        $data['creditCardAccountUpdates'] = array(
            'BillingAddress' => array(
				'NameOnCard' => $this->getNameOnCard(),
				'AddressLineOne' => $card->getBillingAddress1(),
				'City' => $card->getBillingCity(),
                'State' => $card->getBillingState(),
                'ZipCode' => substr($card->getBillingPostcode(), 0, 5),
                'Phone' => preg_replace("/[^0-9]/", '', $card->getBillingPhone()),
                'CustomerReferenceCode' => $this->getCustomerReferenceCode(),
			),
            'Customer' => array(
                'FirstName' => $card->getFirstName(),
                'LastName' => $card->getLastName(),
                'AdressLineOne' => $card->getBillingAddress1(),
                'City' => $card->getBillingCity(),
                'State' => $card->getBillingState(),
                'Zip' => substr($card->getBillingPostcode(), 0, 5),
                'CustomerReferenceCode' => $this->getCustomerReferenceCode(),
            ),
        );

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
        
        $response = call_user_func_array(array($this->soap, 'UpdateCreditCardAccount'), array($data));

        return $this->response = new Response($this, $response);
    }
}
