<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\PaymentVision\CommonParametersTrait;
use Omnipay\PaymentVision\CreditCardHelper;

class CreateCardRequest extends AbstractRequest
{
    use CommonParametersTrait;

    /**
     * SoapClient Class
     */
    private $soap = null;

    public function getSoap()
    {
        return $this->soap;
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('card', 'nameOnCard', 'customerReferenceCode');
        /** @var CreditCard $card */
        $card = $this->getCard();
        $card->validate();

        $data = array();

        $data['authentication'] = $this->getAuthenticationParams();

        $data['creditCardAccount'] = array(
            'CreditCardNumber' => $card->getNumber(),
            'CreditCardExpirationMonth' => CreditCardHelper::formatExpiryMonth($card->getExpiryMonth()),
            'CreditCardExpirationYear' => $card->getExpiryYear(),
            'CVVCode' => $card->getCvv(),
            'CardType' => CreditCardHelper::paymentVisionCardType($card->getBrand()),
            'BillingAddress' => array(
				'NameOnCard' => $this->getNameOnCard(),
				'AddressLineOne' => $card->getBillingAddress1(),
				'City' => $card->getBillingCity(),
                'State' => $card->getBillingState(),
                'ZipCode' => substr($card->getBillingPostcode(), 0, 5),
				'Phone' => preg_replace("/[^0-9]/", '', $card->getBillingPhone()),
			),
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
        if (true === $this->getStubMode()) {
            $response = $this->getFakeResponse($data);
            return $this->response = new FakeResponse($this, $response);
        }

        if (!$this->soap) {
            $this->soap = new \SoapClient($this->getWsdl(), array('trace' => $this->getTestMode()));
        }

        $response = call_user_func_array(array($this->soap, 'AddCreditCardAccount'), array($data));

        return $this->response = new Response($this, $response);
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
            'AddCreditCardAccountResult' => [
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => '',
                    ]
                ],
                'ReferenceID' => 'C' . $dateString,
                'TimeReceived' => date('n/j/Y g:i:s A'),
                'CustomerReferenceCode' => 'CU' . $dateString,
                'CreditCard' => [
                    'CreditCardNumber' => 'XXXXXXXXXXXX' . substr($data['creditCardAccount']['CreditCardNumber'], -4, 4),
                    'CreditCardExpirationMonth' => $data['creditCardAccount']['CreditCardExpirationMonth'],
                    'CreditCardExpirationYear' => date('Y', strtotime('+1 year')),
                    'CVVCode' => '',
                    'CardType' => 'Visa',
                    'BillingAddress' => $data['creditCardAccount']['BillingAddress'],
                    'FulfillmentGateway' => '',
                    'AccountUsePreferenceType' => 'MultiUse',
                    'CreditCardBinType' => 'Credit',
                ]
            ]
        ];
    }
}
