<?php

namespace Omnipay\PaymentVision\Message;

class PurchaseViaBankRequest extends AbstractRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate(
            'type',
            'routingNumber',
            'accountNumber',
            'firstName',
            'lastName',
            'billingAddress1',
            'billingCity',
            'billingState',
            'billingPostcode',
            'amount'
        );

        $data = array();

        $data['authentication'] = $this->getAuthenticationParams();

        $data['bankAccount'] = array(
            'AccountType' => $this->getType(),
            'ABA' => $this->getRoutingNumber(),
            'AccountNumber' => $this->getAccountNumber(),
            'BillingAddress' => array_filter([
                'NameOnAccount' => $this->getNameOnAccount(),
                'AddressLineOne' => $this->getBillingAddress1(),
                'City' => $this->getBillingCity(),
                'State' => $this->getBillingState(),
                'Zip' => substr($this->getBillingPostcode(), 0, 5),
                'Phone' => stripDigits($this->getBillingPhone()),
            ]),
            'AccountUsePreferenceType' => 'MultiUse',
            'CheckMICROption' => array(
                'CheckNumberPositionType' => 'RightOfAccount',
                'CheckNumberAuxiliaryPositionType' => 'PrependToCheckNumber',
                'CheckStockType' => 'Business',
            ),
        );

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

    public function getRequestName() : string
    {
        return 'MakeACHPayment';
    }

    /**
     * Get fake response specific to this request
     *
     * @param mixed $data The data that would otherwise be sent
     * @return object
     */
    public function getFakeResponse($data)
    {
        $dateString = date('YmdHis');
        return (object) [
            'MakeACHPaymentResult' => [
                'ReferenceID' => 'B' . $dateString,
                'TimeReceived' => date('n/j/Y g:i:s A'),
                'TransactionStatus' => 'Pending',
                'CaptureTrackingNumber' => 'API' . $dateString,
                'TransactionReferenceCode' => 'STUB' . $dateString,
                'CustomerReferenceCode' => 'CU' . $dateString,
                'BankAccount' => [
                    'ABA' => $data['bankAccount']['ABA'],
                    'AccountNumber' => $data['bankAccount']['AccountNumber'],
                    'AccountType' => $data['bankAccount']['AccountType'],
                    'AccountOwnerType' => '',
                    'BillingAddress' => $data['bankAccount']['BillingAddress'],
                    'CustomBank' => '',
                    'CheckMICROption' => $data['bankAccount']['CheckMICROption'],
                    'AccountUsePreferenceType' => $data['bankAccount']['AccountUsePreferenceType'],
                ],
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => '',
                    ]
                ],
                'Amount' => $data['achPayment']['Amount'],
                'PrincipalAmount' => $data['achPayment']['Amount'],
                'ConvenienceFee' => '0.00',
            ]
        ];
    }
}
