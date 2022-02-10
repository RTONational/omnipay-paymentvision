<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\PaymentVision\CommonParametersTrait;

class UpdateBankAccountRequest extends AbstractRequest
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
        $this->validate('sessionId', 'referenceId');

        $data = array();

        $data['sessionID'] = $this->getSessionId();

        $data['referenceID'] = $this->getReferenceId();

        $data['bankAccountUpdates'] = array(
			'AccountType' => $this->getType(),
			'BillingAddress' => array(
				'NameOnAccount' => $this->getNameOnAccount(),
				'AddressLineOne' => $this->getBillingAddress1(),
				'City' => $this->getBillingCity(),
				'State' => $this->getBillingState(),
				'Zip' => substr($this->getBillingPostcode(), 0, 5),
				'Phone' => preg_replace("/[^0-9]/", '', $this->getBillingPhone()),
                'CustomerReferenceCode' => $this->getCustomerReferenceCode()
            ),
            'Customer' => array(
                'FirstName' => $this->getFirstName(),
                'LastName' => $this->getLastName(),
                'AdressLineOne' => $this->getBillingAddress1(),
                'City' => $this->getBillingCity(),
                'State' => $this->getBillingState(),
                'Zip' => substr($this->getBillingPostcode(), 0, 5),
                'CustomerReferenceCode' => $this->getCustomerReferenceCode()
            ),
        );

        $data['reason'] = 'None given';

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
        
        $response = call_user_func_array(array($this->soap, 'UpdateBankAccount'), array($data));

        return $this->response = new Response($this, $response);
    }
}
