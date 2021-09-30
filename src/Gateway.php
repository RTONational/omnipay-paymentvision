<?php

namespace Omnipay\PaymentVision;

use Omnipay\Common\AbstractGateway;

/**
 * PaymentVision Gateway Class
 */
class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'PaymentVision';
    }

    /***
     * Parameter getters and setters
     */

    public function getDefaultParameters()
    {
        return array(
            'pvLogin' => '',
            'pvPassword' => '',
            'pvAPIKey' => '',
            'pvToken' => '',
            'merchantPayeeCode' => '',
            'liveWsdl' => 'https://portal.paymentvision.com/api/api.asmx?wsdl',
            'testWsdl' => 'https://demo-portal.paymentvision.com/api/api.asmx?wsdl',
            'holdForApproval' => false,
            'isRecurring' => false,
            'testMode' => false
        );
    }

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

    public function getMerchantPayeeCode()
    {
        return $this->getParameter('merchantPayeeCode');
    }

    public function setMerchantPayeeCode($value)
    {
        return $this->setParameter('merchantPayeeCode', $value);
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

    public function getHoldForApproval()
    {
        return $this->getParameter('holdForApproval');
    }

    public function setHoldForApproval($value)
    {
        return $this->setParameter('holdForApproval', $value);
    }

    /***
     * Request methods
     */

    public function login(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\LoginRequest', $parameters);
    }

    /***
     * One-time bank account transactions
     */

    public function purchaseViaBank(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\PurchaseViaBankRequest', $parameters);
    }

    /***
     * One-time credit card transactions
     */

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\PurchaseRequest', $parameters);
    }

    public function getTransactionDetails(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\TransactionDetailsRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\RefundRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\VoidRequest', $parameters);
    }

    /***
     * Saved bank account transactions
     */

    public function createBankAccount(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\CreateBankAccountRequest', $parameters);
    }

    public function updateBankAccount(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\UpdateBankAccountRequest', $parameters);
    }

    public function deleteBankAccount(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\DeleteBankAccountRequest', $parameters);
    }

    public function chargeBankAccount(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\ChargeBankAccountRequest', $parameters);
    }

    /***
     * Saved credit card account transactions
     */

    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\CreateCardRequest', $parameters);
    }

    public function updateCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\UpdateCardRequest', $parameters);
    }

    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\DeleteCardRequest', $parameters);
    }

    public function chargeCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\ChargeCardRequest', $parameters);
    }

    public function financialAccounts(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\FinancialAccountsRequest', $parameters);
    }

    /***
     * Saved customer account transactions
     */

    public function updateCustomer(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PaymentVision\Message\UpdateCustomerRequest', $parameters);
    }
}
