<?php

namespace Omnipay\PaymentVision;

trait CommonParametersTrait
{
    public function getSessionId()
    {
        return $this->getParameter('sessionId');
    }

    public function setSessionId($value)
    {
        return $this->setParameter('sessionId', $value);
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

    public function getStubMode()
    {
        return $this->getParameter('stubMode');
    }

    public function setStubMode($value)
    {
        return $this->setParameter('stubMode', $value);
    }

    public function getReferenceId()
    {
        return $this->getParameter('referenceId');
    }

    public function setReferenceId($value)
    {
        return $this->setParameter('referenceId', $value);
    }

    public function getTransactionId()
    {
        return $this->getParameter('transactionId');
    }

    public function setTransactionId($value)
    {
        return $this->setParameter('transactionId', $value);
    }

    public function getExternalRequestId()
    {
        return $this->getParameter('externalRequestId');
    }

    public function setExternalRequestId($value)
    {
        return $this->setParameter('externalRequestId', $value);
    }

    public function getRefundReason()
    {
        return $this->getParameter('refundReason');
    }

    public function setRefundReason($value)
    {
        return $this->setParameter('refundReason', $value);
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

    public function getNameOnCard()
    {
        return $this->getParameter('nameOnCard');
    }

    public function setNameOnCard($value)
    {
        return $this->setParameter('nameOnCard', $value);
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

    public function getNewCustomerReferenceCode()
    {
        return $this->getParameter('newCustomerReferenceCode');
    }

    public function setNewCustomerReferenceCode($value)
    {
        return $this->setParameter('newCustomerReferenceCode', $value);
    }
}
