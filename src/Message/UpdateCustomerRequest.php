<?php

namespace Omnipay\PaymentVision\Message;

class UpdateCustomerRequest extends AbstractRequest
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

        $data['customer'] = array(
            'FirstName' => $this->getFirstName(),
            'LastName' => $this->getLastName(),
            'AdressLineOne' => $this->getBillingAddress1(),
            'City' => $this->getBillingCity(),
            'State' => $this->getBillingState(),
            'Zip' => substr($this->getBillingPostcode(), 0, 5),
            'CustomerReferenceCode' => $this->getNewCustomerReferenceCode()
        );

        return $data;
    }

    public function getRequestName() : string
    {
        return 'UpdateCustomerInformation';
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
            'UpdateCustomerInformationResult' => [
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => ''
                    ]
                ],
                'TimeReceived' => date('n/j/Y g:i:s A'),
                'Customer' => [
                    'CustomerReferenceCode' => $data['customerReferenceCode'],
                    'FirstName' => $data['customer']['FirstName'],
                    'LastName' => $data['customer']['LastName'],
                    'AdressLineOne' => $data['customer']['AdressLineOne'],
                    'AddressLineTwo' => '',
                    'City' => $data['customer']['City'],
                    'State' => $data['customer']['State'],
                    'Zip' => $data['customer']['Zip'],
                    'HomePhone' => '               ',
                    'WorkPhone' => '               ',
                    'Email' => ''
                ]
            ]
        ];
    }
}
