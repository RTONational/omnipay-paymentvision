<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\PaymentVision\CommonParametersTrait;

/**
 * Send a request for voiding a transaction
 */
class VoidRequest extends AbstractRequest
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
        $data = array();

        $data['sessionID'] = $this->getSessionId();
        $data['externalRequestID'] = $this->getExternalRequestId();
        $data['reason'] = $this->getRefundReason();

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
        if (!$this->soap) {
            $this->soap = new \SoapClient($this->getWsdl(), array('trace' => $this->getTestMode()));
        }
        
        $response = call_user_func_array(array($this->soap, 'VoidAPIPaymentRequest'), array($data));

        return $this->response = new Response($this, $response);
    }
}
