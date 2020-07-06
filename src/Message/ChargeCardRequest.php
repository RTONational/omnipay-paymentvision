<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractRequest;

class ChargeCardRequest extends AbstractRequest
{
    /**
     * SoapClient Class
     */
    private $soap = null;

    public function getSoap()
    {
        return $this->soap;
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

    public function getReferenceId()
    {
        return $this->getParameter('referenceId');
    }

    public function setReferenceId($value)
    {
        return $this->setParameter('referenceId', $value);
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

    public function getSettlementDate()
    {
        return $this->getParameter('settlementDate');
    }

    public function setSettlementDate($value)
    {
        return $this->setParameter('settlementDate', $value);
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
        $this->validate('amount', 'referenceId', 'card');
        /** @var CreditCard $card */
        $card = $this->getCard();

        $data = array();

        $data['authentication'] = $this->getAuthenticationParams();

        $data['referenceID'] = $this->getReferenceId();

        $data['creditCardPayment'] = array(
            'Amount' => $this->getAmount(),
            'Comment' => $this->getComment(),
            'UserDefinedOne' => $this->getUserDefinedOne(),
            'MerchantPayeeCode' => $this->getMerchantPayeeCode(),
            'HoldForApproval' => $this->getHoldForApproval(),
            'IsRecurring' => $this->getIsRecurring(),
        );
        if ($settlementDate = $this->getSettlementDate()) {
			$data['creditCardPayment']['SettlementDate'] = date('m/d/Y', strtotime($settlementDate));
		}
        
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
        
        $response = call_user_func_array(array($this->soap, 'MakeIdBasedCreditCardPayment'), array($data));

        return $this->response = new Response($this, $response);
    }
}
