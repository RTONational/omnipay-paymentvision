<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractRequest;

/**
 * Log in to gateway and get a session id for certain requests
 */
class LoginRequest extends AbstractRequest
{
    /**
     * SoapClient Class
     */
    public $soap = null;

    public function getPvLogin()
    {
        return $this->getParameter('pvLogin');
    }

    public function setPvLogin($value)
    {
        return $this->setParameter('pvLogin', $value);
    }

    public function getPvPassword()
    {
        return $this->getParameter('pvPassword');
    }

    public function setPvPassword($value)
    {
        return $this->setParameter('pvPassword', $value);
    }

    public function getPvAPIKey()
    {
        return $this->getParameter('pvAPIKey');
    }

    public function setPvAPIKey($value)
    {
        return $this->setParameter('pvAPIKey', $value);
    }

    public function getPvToken()
    {
        return $this->getParameter('pvToken');
    }

    public function setPvToken($value)
    {
        return $this->setParameter('pvToken', $value);
    }

    public function getLiveWsdl()
    {
        return $this->getParameter('liveWsdl');
    }

    public function setLiveWsdl($value)
    {
        return $this->setParameter('liveWsdl', $value);
    }

    public function getTestWsdl()
    {
        return $this->getParameter('testWsdl');
    }

    public function setTestWsdl($value)
    {
        return $this->setParameter('testWsdl', $value);
    }

    public function getWsdl()
    {
        return $this->getTestMode() ? $this->getTestWsdl() : $this->getLiveWsdl();
    }

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

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        if (!$this->soap) {
            $this->soap = new \SoapClient($this->getWsdl(), array('trace' => $this->getTestMode()));
        }
        
        $response = call_user_func_array(array($this->soap, 'Login'), array($data));

        return new Response($this, $response);
    }
}
