<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\PaymentVision\Helpers;

class UpdateCardRequest extends AbstractRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('sessionId', 'referenceId', 'card', 'nameOnCard', 'customerReferenceCode');
        /** @var CreditCard $card */
        $card = $this->getCard();

        $data = array();

        $data['sessionID'] = $this->getSessionId();

        $data['referenceID'] = $this->getReferenceId();

        $data['creditCardAccountUpdates'] = array(
            'BillingAddress' => array_filter([
                'NameOnCard' => $this->getNameOnCard(),
                'AddressLineOne' => $card->getBillingAddress1(),
                'City' => $card->getBillingCity(),
                'State' => $card->getBillingState(),
                'ZipCode' => substr($card->getBillingPostcode(), 0, 5),
                'Phone' => Helpers::stripNondigits($card->getBillingPhone()),
                'CustomerReferenceCode' => $this->getCustomerReferenceCode(),
            ]),
            'Customer' => array(
                'FirstName' => $card->getFirstName(),
                'LastName' => $card->getLastName(),
                'AdressLineOne' => $card->getBillingAddress1(),
                'City' => $card->getBillingCity(),
                'State' => $card->getBillingState(),
                'Zip' => substr($card->getBillingPostcode(), 0, 5),
                'CustomerReferenceCode' => $this->getCustomerReferenceCode(),
            ),
        );

        return $data;
    }

    public function getRequestName() : string
    {
        return 'UpdateCreditCardAccount';
    }

    /**
     * Get fake response specific to this request
     *
     * @param  mixed $data The data that would otherwise be sent
     * @return object
     */
    public function getFakeResponse($data)
    {
        return (object) [
            'UpdateCreditCardAccountResult' => [
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => ''
                    ]
                ],
                'TimeReceived' => date('n/j/Y g:i:s A'),
                'CreditCardAccountToken' => [
                    'ReferenceID' => $data['referenceID'],
                    'CreditCardAccount' => [
                        'CreditCardNumber' => 'XXXXXXXXXXXX1111',
                        'CreditCardExpirationMonth' => '12',
                        'CreditCardExpirationYear' => date('Y', strtotime('+1 year')),
                        'CardType' => 'Visa',
                        'BillingAddress' => [
                            'NameOnCard' => $data['creditCardAccountUpdates']['BillingAddress']['NameOnCard'],
                            'AddressLineOne' => $data['creditCardAccountUpdates']['BillingAddress']['AddressLineOne'],
                            'City' => $data['creditCardAccountUpdates']['BillingAddress']['City'],
                            'State' => $data['creditCardAccountUpdates']['BillingAddress']['State'],
                            'ZipCode' => $data['creditCardAccountUpdates']['BillingAddress']['ZipCode'],
                            'Phone' => $data['creditCardAccountUpdates']['BillingAddress']['Phone']
                        ],
                        'FulfillmentGateway' => '',
                        'AccountUsePreferenceType' => 'MultiUse',
                        'CreditCardBinType' => ''
                    ],
                    'FinancialAccountStatusType' => 'Active',
                    'LastUsed' => '0001-01-01T00:00:00',
                    'LastUpdated' => '0001-01-01T00:00:00',
                    'Created' => '0001-01-01T00:00:00'
                ]
            ]
        ];
    }
}
