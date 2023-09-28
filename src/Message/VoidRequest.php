<?php

namespace Omnipay\PaymentVision\Message;

/**
 * Send a request for voiding a transaction
 */
class VoidRequest extends AbstractRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $data = array();

        $data['sessionID'] = $this->getSessionId();
        $data['externalRequestID'] = $this->getExternalRequestId();
        $data['reason'] = $this->getRefundReason();

        return $data;
    }

    public function getRequestName() : string
    {
        return 'VoidAPIPaymentRequest';
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
            'VoidAPIPaymentRequestResult' => [
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => '',
                    ],
                ],
                'TimeReceived' => date('n/j/Y g:i:s A'),
                'RecordCount' => '1',
                'TransactionResponses' => [
                    'anyType' => [
                        '@attributes' => [
                            'xsi:type' => 'CreditCardPaymentResponse',
                        ],
                        'ReferenceID' => 'C' . $dateString,
                        'TimeReceived' => date('n/j/Y g:i:s A'),
                        'TransactionStatus' => 'Cancelled',
                        'CaptureTrackingNumber' => 'API' . $dateString,
                        'TransactionReferenceCode' => 'STUB' . $dateString,
                        'CustomerReferenceCode' => 'CU' . $dateString,
                        'Amount' => '100.0000',
                        'PrincipalAmount' => '100.0000',
                        'ConvenienceFee' => '0.0000',
                        'CreditCardAccount' => [
                            'CreditCardNumber' => 'XXXXXXXXXXXX1111',
                            'CreditCardExpirationMonth' => '12',
                            'CreditCardExpirationYear' => date('Y', strtotime('+1 year')),
                            'CardType' => 'Visa',
                            'BillingAddress' => [
                                'NameOnCard' => 'Testy Tester',
                                'AddressLineOne' => '1502 N Main St.',
                                'AddressLineTwo' => [],
                                'City' => 'Greenville',
                                'State' => 'SC',
                                'ZipCode' => '29609',
                                'Phone' => [],
                            ],
                            'FulfillmentGateway' => [
                                '@attributes' => [
                                    'xsi:nil' => 'true',
                                ],
                            ],
                            'AccountUsePreferenceType' => [
                                '@attributes' => [
                                    'xsi:nil' => 'true',
                                ],
                            ],
                            'CreditCardBinType' => [
                                '@attributes' => [
                                    'xsi:nil' => 'true',
                                ],
                            ],
                        ],
                        'Responses' => [
                            'Response' => [
                                'ResponseCode' => '1000',
                                'ErrorMessage' => '',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
