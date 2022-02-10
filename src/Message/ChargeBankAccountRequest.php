<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\PaymentVision\CommonParametersTrait;

class ChargeBankAccountRequest extends AbstractRequest
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
        $this->validate('amount', 'referenceId');

        $data = array();

        $data['authentication'] = $this->getAuthenticationParams();

        $data['referenceID'] = $this->getReferenceId();

        $data['achPayment'] = array(
            'Amount' => $this->getAmount(),
            'Comment' => $this->getComment(),
            'UserDefinedOne' => substr($this->getUserDefinedOne(), 0, 50),
            'SecCode' => 'WEB',
            'MerchantPayeeCode' => $this->getMerchantPayeeCode(),
            'HoldForApproval' => $this->getHoldForApproval(),
            'IsRecurring' => $this->getIsRecurring(),
        );
        if ($settlementDate = $this->getSettlementDate()) {
			$data['achPayment']['SettlementDate'] = date('m/d/Y', strtotime($settlementDate));
		}
        
        $data['customer'] = array(
            'FirstName' => $this->getFirstName(),
            'LastName' => $this->getLastName(),
            'AdressLineOne' => $this->getBillingAddress1(),
            'City' => $this->getBillingCity(),
            'State' => $this->getBillingState(),
            'Zip' => substr($this->getBillingPostcode(), 0, 5),
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
        
        $response = call_user_func_array(array($this->soap, 'MakeIdBasedACHPayment'), array($data));

        return $this->response = new Response($this, $response);
    }
}
