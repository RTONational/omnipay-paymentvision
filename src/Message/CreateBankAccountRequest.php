<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\PaymentVision\Helpers;

class CreateBankAccountRequest extends AbstractRequest
{
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
            'BillingAddress' => array_filter([
                'NameOnAccount' => $this->getNameOnAccount(),
                'AddressLineOne' => $this->getBillingAddress1(),
                'City' => $this->getBillingCity(),
                'State' => $this->getBillingState(),
                'Zip' => substr($this->getBillingPostcode(), 0, 5),
                'Phone' => Helpers::stripNondigits($this->getBillingPhone()),
            ]),
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

    public function getRequestName() : string
    {
        return 'AddBankAccount';
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
            'AddBankAccountResult' => [
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => ''
                    ]
                ],
                'ReferenceID' => 'C' . $dateString,
                'TimeReceived' => date('n/j/Y g:i:s A'),
                'CustomerReferenceCode' => 'CU' . $dateString,
                'Bank' => [
                    'ABA' => $data['bankAccount']['ABA'],
                    'AccountNumber' => $data['bankAccount']['AccountNumber'],
                    'AccountType' => $data['bankAccount']['AccountType'],
                    'AccountOwnerType' => '',
                    'BillingAddress' => $data['bankAccount']['BillingAddress'],
                    'CustomBank' => [
                        'BankName' => '',
                        'BankCity' => '',
                        'BankState' => ''
                    ],
                    'CheckMICROption' => $data['bankAccount']['CheckMICROption'],
                    'AccountUsePreferenceType' => $data['bankAccount']['AccountUsePreferenceType']
                ]
            ]
        ];
    }
}
