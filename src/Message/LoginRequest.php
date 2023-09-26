<?php

namespace Omnipay\PaymentVision\Message;

/**
 * Log in to gateway and get a session id for certain requests
 */
class LoginRequest extends AbstractRequest
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

        $data['authentication'] = $this->getAuthenticationParams();

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
        return 'Login';
    }


    /**
     * Get fake response specific to this request
     *
     * @param  mixed $data The data that would otherwise be sent
     * @return object
     */
    public function getFakeResponse($data)
    {
        $uuid = vsprintf('STUB%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(14)), 4));
        return (object) [
            'LoginResult' => [
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => ''
                    ]
                ],
                'SessionID' => $uuid
            ]
        ];
    }
}
