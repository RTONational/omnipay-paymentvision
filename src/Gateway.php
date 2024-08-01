<?php

namespace Omnipay\PaymentVision;

use Omnipay\Common\AbstractGateway;
use Omnipay\PaymentVision\Message\ChargeBankAccountRequest;
use Omnipay\PaymentVision\Message\ChargeCardRequest;
use Omnipay\PaymentVision\Message\CreateBankAccountRequest;
use Omnipay\PaymentVision\Message\CreateCardRequest;
use Omnipay\PaymentVision\Message\DeleteBankAccountRequest;
use Omnipay\PaymentVision\Message\DeleteCardRequest;
use Omnipay\PaymentVision\Message\FinancialAccountsRequest;
use Omnipay\PaymentVision\Message\LoginRequest;
use Omnipay\PaymentVision\Message\PurchaseRequest;
use Omnipay\PaymentVision\Message\PurchaseViaBankRequest;
use Omnipay\PaymentVision\Message\RefundRequest;
use Omnipay\PaymentVision\Message\ReplaceCardRequest;
use Omnipay\PaymentVision\Message\TransactionDetailsRequest;
use Omnipay\PaymentVision\Message\UpdateBankAccountRequest;
use Omnipay\PaymentVision\Message\UpdateCardRequest;
use Omnipay\PaymentVision\Message\UpdateCustomerRequest;
use Omnipay\PaymentVision\Message\VoidRequest;

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
            // 'liveWsdl' => 'https://portal.paymentvision.com/api/api.asmx?wsdl',
            // 'testWsdl' => 'https://demo-portal.paymentvision.com/api/api.asmx?wsdl',
            // currently using locally stored WSDL to lock in compatibility
            'liveWsdl' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wsdl' . DIRECTORY_SEPARATOR . '2024-07-31.wsdl.xml',
            'testWsdl' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'wsdl' . DIRECTORY_SEPARATOR . 'test-2024-07-31.wsdl.xml',
            'holdForApproval' => false,
            'isRecurring' => false,
            'testMode' => false,
            'stubMode' => false
        );
    }

    /***
     * Request methods
     */

    public function login(array $parameters = array())
    {
        return $this->createRequest(LoginRequest::class, $parameters);
    }

    /***
     * One-time bank account transactions
     */

    public function purchaseViaBank(array $parameters = array())
    {
        return $this->createRequest(PurchaseViaBankRequest::class, $parameters);
    }

    /***
     * One-time credit card transactions
     */

    public function purchase(array $parameters = array())
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    public function getTransactionDetails(array $parameters = array())
    {
        return $this->createRequest(TransactionDetailsRequest::class, $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest(VoidRequest::class, $parameters);
    }

    /***
     * Saved bank account transactions
     */

    public function createBankAccount(array $parameters = array())
    {
        return $this->createRequest(CreateBankAccountRequest::class, $parameters);
    }

    public function updateBankAccount(array $parameters = array())
    {
        return $this->createRequest(UpdateBankAccountRequest::class, $parameters);
    }

    public function deleteBankAccount(array $parameters = array())
    {
        return $this->createRequest(DeleteBankAccountRequest::class, $parameters);
    }

    public function chargeBankAccount(array $parameters = array())
    {
        return $this->createRequest(ChargeBankAccountRequest::class, $parameters);
    }

    /***
     * Saved credit card account transactions
     */

    public function createCard(array $parameters = array())
    {
        return $this->createRequest(CreateCardRequest::class, $parameters);
    }

    public function updateCard(array $parameters = array())
    {
        return $this->createRequest(UpdateCardRequest::class, $parameters);
    }

    public function replaceCard(array $parameters = array())
    {
        return $this->createRequest(ReplaceCardRequest::class, $parameters);
    }

    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest(DeleteCardRequest::class, $parameters);
    }

    public function chargeCard(array $parameters = array())
    {
        return $this->createRequest(ChargeCardRequest::class, $parameters);
    }

    public function financialAccounts(array $parameters = array())
    {
        return $this->createRequest(FinancialAccountsRequest::class, $parameters);
    }

    /***
     * Saved customer account transactions
     */

    public function updateCustomer(array $parameters = array())
    {
        return $this->createRequest(UpdateCustomerRequest::class, $parameters);
    }
}
