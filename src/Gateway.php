<?php

namespace Omnipay\PaymentVision;

use Omnipay\Common\AbstractGateway;

/**
 * PaymentVision Gateway Class
 */
class Gateway extends AbstractGateway
{
    use CommonParametersTrait;

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
