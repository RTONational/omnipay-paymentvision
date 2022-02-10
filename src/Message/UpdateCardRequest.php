<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\PaymentVision\CommonParametersTrait;

class UpdateCardRequest extends AbstractRequest
{
    use CommonParametersTrait;

    /**
     * SoapClient Class
     */
    private $soap = null;

    public function getSoap()
    {
        return $this->soap;
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
