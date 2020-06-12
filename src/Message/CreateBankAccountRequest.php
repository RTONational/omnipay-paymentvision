<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractRequest;

class CreateBankAccountRequest extends AbstractRequest
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

    public function getType()
    {
        return $this->getParameter('type');
    }

    public function setType($value)
    {
        return $this->setParameter('type', $value);
    }

    public function getRoutingNumber()
    {
        return $this->getParameter('routingNumber');
    }

    public function setRoutingNumber($value)
    {
        return $this->setParameter('routingNumber', $value);
    }

    public function getAccountNumber()
    {
        return $this->getParameter('accountNumber');
    }

    public function setAccountNumber($value)
    {
        return $this->setParameter('accountNumber', $value);
    }

    public function getNameOnAccount()
    {
        return $this->getParameter('nameOnAccount');
    }

    public function setNameOnAccount($value)
    {
        return $this->setParameter('nameOnAccount', $value);
    }

    public function getFirstName()
    {
        return $this->getParameter('firstName');
    }

    public function setFirstName($value)
    {
        return $this->setParameter('firstName', $value);
    }

    public function getLastName()
    {
        return $this->getParameter('lastName');
    }

    public function setLastName($value)
    {
        return $this->setParameter('lastName', $value);
    }

    public function getBillingAddress1()
    {
        return $this->getParameter('billingAddress1');
    }

    public function setBillingAddress1($value)
    {
        return $this->setParameter('billingAddress1', $value);
    }

    public function getBillingCity()
    {
        return $this->getParameter('billingCity');
    }

    public function setBillingCity($value)
    {
        return $this->setParameter('billingCity', $value);
    }

    public function getBillingState()
    {
        return $this->getParameter('billingState');
    }

    public function setBillingState($value)
    {
        return $this->setParameter('billingState', $value);
    }

    public function getBillingPostcode()
    {
        return $this->getParameter('billingPostcode');
    }

    public function setBillingPostcode($value)
    {
        return $this->setParameter('billingPostcode', $value);
    }

    public function getBillingPhone()
    {
        return $this->getParameter('billingPhone');
    }

    public function setBillingPhone($value)
    {
        return $this->setParameter('billingPhone', $value);
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
        $this->validate('type', 'routingNumber', 'accountNumber');

        $data = array();

        $data['authentication'] = $this->getAuthenticationParams();

        $data['bankAccount'] = array(
			'AccountType' => $this->getType(),
			'ABA' => $this->getRoutingNumber(),
			'AccountNumber' => $this->getAccountNumber(),
			'BillingAddress' => array(
				'NameOnAccount' => $this->getNameOnAccount(),
				'AddressLineOne' => $this->getBillingAddress1(),
				'City' => $this->getBillingCity(),
				'State' => $this->getBillingState(),
				'Zip' => substr($this->getBillingPostcode(), 0, 5),
				'Phone' => preg_replace("/[^0-9]/", '', $this->getBillingPhone()),
			),
            'AccountUsePreferenceType' => 'MultiUse',
            'CheckMICROption' => array(
                'CheckNumberPositionType' => 'RightOfAccount',
                'CheckNumberAuxiliaryPositionType' => 'PrependToCheckNumber',
                'CheckStockType' => 'Business',
            ),
		);
		
		$data['customer'] = array(
			'FirstName' => $this->getFirstName(),
			'LastName' => $this->getLastName(),
			'AdressLineOne' => $this->getBillingAddress1(),
			'City' => $this->getBillingCity(),
			'State' => $this->getBillingState(),
			'Zip' => substr($this->getBillingPostcode(), 0, 5),
            'CustomerReferenceCode' => $this->getCustomerReferenceCode()
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
        
        $response = call_user_func_array(array($this->soap, 'AddBankAccount'), array($data));

        return $this->response = new Response($this, $response);
    }
}
