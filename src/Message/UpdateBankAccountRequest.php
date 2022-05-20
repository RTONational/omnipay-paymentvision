<?php

namespace Omnipay\PaymentVision\Message;

class UpdateBankAccountRequest extends AbstractRequest
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

        $data['bankAccountUpdates'] = array(
            'AccountType' => $this->getType(),
            'BillingAddress' => array(
                'NameOnAccount' => $this->getNameOnAccount(),
                'AddressLineOne' => $this->getBillingAddress1(),
                'City' => $this->getBillingCity(),
                'State' => $this->getBillingState(),
                'Zip' => substr($this->getBillingPostcode(), 0, 5),
                'Phone' => preg_replace("/[^0-9]/", '', $this->getBillingPhone()),
                'CustomerReferenceCode' => $this->getCustomerReferenceCode()
            ),
            'Customer' => array(
                'FirstName' => $this->getFirstName(),
                'LastName' => $this->getLastName(),
                'AdressLineOne' => $this->getBillingAddress1(),
                'City' => $this->getBillingCity(),
                'State' => $this->getBillingState(),
                'Zip' => substr($this->getBillingPostcode(), 0, 5),
                'CustomerReferenceCode' => $this->getCustomerReferenceCode()
            ),
        );

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

        $response = call_user_func_array(array($this->soap, 'UpdateBankAccount'), array($data));

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
        return (object) [
            'UpdateBankAccountResult' => [
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => ''
                    ]
                ],
                'TimeReceived' => date('n/j/Y g:i:s A'),
                'BankAccountToken' => [
                    'ReferenceID' => $data['referenceID'],
                    'BankAccount' => [
                        'ABA' => '122199983',
                        'AccountNumber' => '12',
                        'AccountType' => 'Checking',
                        'AccountOwnerType' => '',
                        'BillingAddress' => [
                            'NameOnAccount' => $data['bankAccountUpdates']['BillingAddress']['NameOnAccount'],
                            'AddressLineOne' => $data['bankAccountUpdates']['BillingAddress']['AddressLineOne'],
                            'City' => $data['bankAccountUpdates']['BillingAddress']['City'],
                            'State' => $data['bankAccountUpdates']['BillingAddress']['State'],
                            'Zip' => $data['bankAccountUpdates']['BillingAddress']['Zip'],
                            'Phone' => $data['bankAccountUpdates']['BillingAddress']['Phone']
                        ],
                        'CustomBank' => [
                            'BankName' => '',
                            'BankCity' => '',
                            'BankState' => ''
                        ],
                        'CheckMICROption' => [
                            'CheckNumberPositionType' => '',
                            'CheckNumberAuxiliaryPositionType' => '',
                            'CheckStockType' => ''
                        ],
                        'AccountUsePreferenceType' => 'MultiUse'
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
