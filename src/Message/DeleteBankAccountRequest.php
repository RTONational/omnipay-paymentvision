<?php

namespace Omnipay\PaymentVision\Message;

class DeleteBankAccountRequest extends AbstractRequest
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

        $data['reason'] = 'None given';

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

        $response = call_user_func_array(array($this->soap, 'UnregisterBankAccount'), array($data));

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
            'UnregisterBankAccountResult' => [
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => ''
                    ]
                ],
                'TimeReceived' => date('n/j/Y g:i:s A'),
                'CustomerReferenceCode' => 'CU' . $dateString,
                'BankAccountToken' => [
                    'ReferenceID' => $data['referenceID'],
                    'BankAccount' => [
                        'ABA' => '122199983',
                        'AccountNumber' => '12',
                        'AccountType' => 'C',
                        'AccountOwnerType' => '',
                        'BillingAddress' => [
                            'NameOnAccount' => 'Testy Tester',
                            'AddressLineOne' => '123 Main Street',
                            'AddressLineTwo' => '',
                            'City' => 'Greenville',
                            'State' => 'SC',
                            'Zip' => '29607',
                            'Phone' => '5555555555',
                        ],
                        'CustomBank' => [
                            'BankName' => '',
                            'BankCity' => '',
                            'BankState' => '',
                        ],
                        'CheckMICROption' => [
                            'CheckNumberPositionType' => '',
                            'CheckNumberAuxiliaryPositionType' => '',
                            'CheckStockType' => '',
                        ],
                        'AccountUsePreferenceType' => 'MultiUse',
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
