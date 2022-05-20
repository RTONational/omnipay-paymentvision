<?php

namespace Omnipay\PaymentVision\Message;

/**
 * Send a request for details of a single transaction specified by transaction reference code
 */
class FinancialAccountsRequest extends AbstractRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->validate('sessionId', 'customerReferenceCode');

        $data = array();

        $data['sessionID'] = $this->getSessionId();

        $data['customerReferenceCode'] = $this->getCustomerReferenceCode();

        // set filter params
        $data['financialAccountTokenFilter']['FinancialAccountTokenTypes'] = null;
        $data['financialAccountTokenFilter']['FinancialAccountStatusTypes'] = 'Active';

        return $data;
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

        $response = call_user_func_array(array($this->soap, 'GetFinancialAccountTokens'), array($data));

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
            'GetFinancialAccountTokensResult' => [
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => '',
                    ],
                ],
                'TimeReceived' => date('n/j/Y g:i:s A'),
                'RecordCount' => 1,
                'FinancialAccountTokens' => [
                    'anyType' => [
                        'enc_type' => 0,
                        'enc_value' => [
                            'ReferenceID' => 'C' . $dateString,
                            'CreditCardAccount' => [
                                'CreditCardNumber' => 'XXXXXXXXXXXX1111',
                                'CreditCardExpirationMonth' => '12',
                                'CreditCardExpirationYear' => date('Y', strtotime('+1 year')),
                                'CardType' => 'Visa',
                                'BillingAddress' => [
                                    'NameOnCard' => 'Testy Tester',
                                    'AddressLineOne' => '1502 N Main St.',
                                    'AddressLineTwo' => '',
                                    'City' => 'Greenville',
                                    'State' => 'SC',
                                    'ZipCode' => '29609',
                                    'Phone' => '               ',
                                ],
                                'FulfillmentGateway' => null,
                                'AccountUsePreferenceType' => 'MultiUse',
                                'CreditCardBinType' => 'Credit',
                            ],
                            'FinancialAccountStatusType' => 'Active',
                            'LastUsed' => '0001-01-01T00:00:00',
                            'LastUpdated' => '2022-02-12T05:11:47.993',
                            'Created' => '2022-02-12T05:11:47.993',
                        ],
                        'enc_stype' => 'CreditCardAccountToken',
                        'enc_ns' => 'http://www.paymentvision.com/API',
                    ],
                ],
            ],
        ];
    }
}
