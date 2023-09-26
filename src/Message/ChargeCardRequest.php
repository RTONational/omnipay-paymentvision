<?php

namespace Omnipay\PaymentVision\Message;

class ChargeCardRequest extends AbstractRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('amount', 'referenceId', 'card');
        /** @var CreditCard $card */
        $card = $this->getCard();

        $data = array();

        $data['authentication'] = $this->getAuthenticationParams();

        $data['referenceID'] = $this->getReferenceId();

        $data['creditCardPayment'] = array(
            'Amount' => $this->getAmount(),
            'Comment' => $this->getComment(),
            'UserDefinedOne' => substr($this->getUserDefinedOne(), 0, 50),
            'MerchantPayeeCode' => $this->getMerchantPayeeCode(),
            'HoldForApproval' => $this->getHoldForApproval(),
            'IsRecurring' => $this->getIsRecurring(),
        );
        if ($settlementDate = $this->getSettlementDate()) {
            $data['creditCardPayment']['SettlementDate'] = date('m/d/Y', strtotime($settlementDate));
        }

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

    public function getRequestName() : string
    {
        return 'MakeIdBasedCreditCardPayment';
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
            'MakeIdBasedCreditCardPaymentResult' => [
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => ''
                    ]
                ],
                'ReferenceID' => $data['referenceID'],
                'TimeReceived' => date('n/j/Y g:i:s A'),
                'TransactionStatus' => 'Processed',
                'CaptureTrackingNumber' => 'API' . $dateString,
                'TransactionReferenceCode' => 'STUB' . $dateString,
                'CustomerReferenceCode' => 'CU' . $dateString,
                'Amount' => $data['creditCardPayment']['Amount'],
                'PrincipalAmount' => $data['creditCardPayment']['Amount'],
                'ConvenienceFee' => '0.00',
                'AVSResponse' => 'BAD',
                'CreditCardNetworkAuthorizationCode' => '999999',
                'CreditCardAccount' => [
                    'CreditCardNumber' => 'XXXXXXXXXXXX1111',
                    'CreditCardExpirationMonth' => '12',
                    'CreditCardExpirationYear' => date('Y', strtotime('+1 year')),
                    'CardType' => 'Visa',
                    'FulfillmentGateway' => '',
                    'AccountUsePreferenceType' => 'MultiUse',
                    'CreditCardBinType' => '',
                ]
            ]
        ];
    }
}
