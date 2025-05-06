<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\PaymentVision\CreditCardHelper;
use Omnipay\PaymentVision\Helpers;

class ReplaceCardRequest extends AbstractRequest
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
        $card->validate();

        $data = array();

        $data['sessionID'] = $this->getSessionId();

        $data['referenceID'] = $this->getReferenceId();

        // Does this need to begin lowercase ("creditCardAccount") or not?
        $data['creditCardAccount'] = array(
            'CreditCardNumber' => $card->getNumber(),
            'CreditCardExpirationMonth' => CreditCardHelper::formatExpiryMonth($card->getExpiryMonth()),
            'CreditCardExpirationYear' => $card->getExpiryYear(),
            'CVVCode' => $card->getCvv(),
            'CardType' => CreditCardHelper::paymentVisionCardType($card->getBrand()),
            'BillingAddress' => array_filter([
                'NameOnCard' => $this->getNameOnCard(),
                'AddressLineOne' => $card->getBillingAddress1(),
                'City' => $card->getBillingCity(),
                'State' => $card->getBillingState(),
                'ZipCode' => substr($card->getBillingPostcode(), 0, 5),
                'Phone' => Helpers::stripNondigits($card->getBillingPhone()),
            ]),
            'AccountUsePreferenceType' => 'MultiUse'
        );

        $data['customer'] = array(
            'FirstName' => $card->getFirstName(),
            'LastName' => $card->getLastName(),
            'AdressLineOne' => $card->getBillingAddress1(),
            'City' => $card->getBillingCity(),
            'State' => $card->getBillingState(),
            'Zip' => substr($card->getBillingPostcode(), 0, 5),
            'CustomerReferenceCode' => $this->getCustomerReferenceCode(),
        );

        $data['merchantPayeeCode'] = $this->getMerchantPayeeCode();

        return $data;
    }

    public function getRequestName() : string
    {
        return 'ReplaceCreditCardAccount';
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
            'ReplaceCreditCardAccountResult' => [
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => ''
                    ]
                ],
                'TimeReceived' => date('n/j/Y g:i:s A'),
                'CustomerReferenceCode' => 'CU' . $dateString,
                'CreditCardAccountToken' => [
                    'ReferenceID' => 'C' . $dateString,
                    'CreditCardAccount' => [
                        'CreditCardNumber' => 'XXXXXXXXXXXX' . substr($data['creditCardAccount']['CreditCardNumber'], -4, 4),
                        'CreditCardExpirationMonth' => $data['creditCardAccount']['CreditCardExpirationMonth'],
                        'CreditCardExpirationYear' => $data['creditCardAccount']['CreditCardExpirationYear'],
                        // 'CVVCode' => '',
                        'CardType' => $data['creditCardAccount']['CardType'],
                        'BillingAddress' => $data['creditCardAccount']['BillingAddress'],
                        'FulfillmentGateway' => '',
                        'AccountUsePreferenceType' => 'MultiUse',
                        'CreditCardBinType' => 'Credit',
                    ],
                    'FinancialAccountStatusType' => 'Active',
                    'LastUsed' => '0001-01-01T00:00:00',
                    'LastUpdated' => '0001-01-01T00:00:00',
                    'Created' => '0001-01-01T00:00:00'
                ]
            ]
        ];
    }

    /**
     * @param mixed $data
     */
    protected function makeResponse($data) : ResponseInterface
    {
        return new ReplaceCardResponse($this, $data);
    }
}
