<?php

namespace Omnipay\PaymentVision\Message;

class DeleteCardRequest extends AbstractRequest
{
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

        return $data;
    }

    public function getRequestName() : string
    {
        return 'UnregisterCreditCardAccount';
    }

    /**
     * Get fake response specific to this request
     *
     * @param  mixed $data The data that would otherwise be sent
     * @return object
     */
    public function getFakeResponse($data)
    {
        $dateString = date('YmdHis');
        return (object) [
            'UnregisterCreditCardAccountResult' => [
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => '',
                    ]
                ],
                'TimeReceived' => date('n/j/Y g:i:s A'),
                'CustomerReferenceCode' => 'CU' . $dateString,
                'CreditCardAccountToken' => [
                    'ReferenceID' => $data['referenceID'],
                    'CreditCardAccount' => [
                        'CreditCardNumber' => 'XXXXXXXXXXXX1111',
                        'CreditCardExpirationMonth' => '12',
                        'CreditCardExpirationYear' => date('Y', strtotime('+1 year')),
                        'CardType' => 'Visa',
                        'BillingAddress' => [
                            'NameOnCard' => 'Testy Tester',
                            'AddressLineOne' => '123 Main Street',
                            'AddressLineTwo' => '',
                            'City' => 'Greenville',
                            'State' => 'SC',
                            'ZipCode' => '29605',
                            'Phone' => '',
                        ],
                        'FulfillmentGateway' => '',
                        'AccountUsePreferenceType' => 'MultiUse',
                        'CreditCardBinType' => '',
                    ],
                    'FinancialAccountStatusType' => 'Active',
                    'LastUsed' => '0001-01-01T00:00:00',
                    'LastUpdated' => '0001-01-01T00:00:00',
                    'Created' => '0001-01-01T00:00:00',
                ]
            ]
        ];
    }
}
