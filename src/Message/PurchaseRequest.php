<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\PaymentVision\CreditCardHelper;

class PurchaseRequest extends AbstractRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('amount', 'card');
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
        );

        $data['creditCardPayment'] = array(
            'MerchantPayeeCode' => $this->getMerchantPayeeCode(),
            'Amount' => $this->getAmount(),
            'Comment' => $this->getComment(),
            'UserDefinedOne' => substr($this->getUserDefinedOne(), 0, 50),
            'HoldForApproval' => $this->getHoldForApproval(),
            'IsRecurring' => $this->getIsRecurring(),
        );

        $data['customer'] = array(
            'FirstName' => $card->getFirstName(),
            'LastName' => $card->getLastName(),
            'AdressLineOne' => $card->getBillingAddress1(),
            'City' => $card->getBillingCity(),
            'State' => $card->getBillingState(),
            'Zip' => substr($card->getBillingPostcode(), 0, 5),
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

    /**
     * Initialize request with parameters
     * @param array $parameters The parameters to send
     */
    // public function initialize(array $parameters = array())
    // {

    // }

    /**
     * Get all request parameters
     *
     * @return array
     */
    // public function getParameters()
    // {

    // }

    /**
     * Get the response to this request (if the request has been sent)
     *
     * @return ResponseInterface
     */
    // public function getResponse()
    // {

    // }

    /**
     * Send the request
     *
     * @return ResponseInterface
     */
    // public function send()
    // {
            // called in AbstractRequest
    // }

    public function getRequestName() : string
    {
        return 'MakeCreditCardPayment';
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
            'MakeCreditCardPaymentResult' => [
                'ReferenceID' => 'C' . $dateString,
                'TimeReceived' => date('n/j/Y g:i:s A'),
                'TransactionStatus' => 'Processed',
                'CaptureTrackingNumber' => 'API' . $dateString,
                'TransactionReferenceCode' => 'STUB' . $dateString,
                'CustomerReferenceCode' => 'CU' . $dateString,
                'Amount' => $data['creditCardPayment']['Amount'],
                'PrincipalAmount' => $data['creditCardPayment']['Amount'],
                'ConvenienceFee' => '0.00',
                'CVVResponse' => 'BAD',
                'AVSResponse' => 'BAD',
                'CreditCardNetworkAuthorizationCode' => '585075',
                'CreditCardAccount' => [
                    'CreditCardNumber' => 'XXXXXXXXXXXX' . substr($data['creditCardAccount']['CreditCardNumber'], -4, 4),
                    'CreditCardExpirationMonth' => $data['creditCardAccount']['CreditCardExpirationMonth'],
                    'CreditCardExpirationYear' => $data['creditCardAccount']['CreditCardExpirationYear'],
                    'CVVCode' => '',
                    'CardType' => $data['creditCardAccount']['CardType'],
                    'FulfillmentGateway' => null,
                    'AccountUsePreferenceType' => 'UnSpecified',
                    'CreditCardBinType' => null
                ],
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => ''
                    ]
                ]
            ]
        ];
    }
}
