<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractResponse;

class Response extends AbstractResponse
{

    public function __construct($request, $data)
    {
        $data = json_decode(json_encode($data), true);

        parent::__construct($request, $data);
    }

    protected function getResponse()
    {
        $response = array();

        foreach ($this->data as $array) {
            if (isset($array['Responses']['Response'])) {
                $response = $array['Responses']['Response'];
                if (isset($response[0]) && is_array($response[0])) {
                    $response = $response[0];
                }
                break;
            }
        }

        return $response;
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->getCode() === '1000' ? true : false;
    }

    /**
     * Response Message
     *
     * @return null|string A response message from the payment gateway
     */
    public function getMessage()
    {
        $response = $this->getResponse();
        return $response['ErrorMessage'];
    }

    /**
     * Response code
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode()
    {
        $response = $this->getResponse();
        return $response['ResponseCode'];
    }

    /**
     * Gateway Reference
     *
     * @return null|string A reference provided by PaymentVision to represent this transaction
     */
    public function getTransactionReference()
    {
        $transactionReference = null;

        array_walk_recursive($this->data, function ($val, $key) use (&$transactionReference) {
            if ($key == 'TransactionReferenceCode') {
                $transactionReference = $val;
            }
        });

        return $transactionReference;
    }

    /**
     * Session ID
     *
     * @return null|string A session id for authenticating multiple requests
     */
    public function getSessionId()
    {
        // response code 1000 is success and returns new session id
		// response code 1012 is error, "user already logged in", and returns current session id
		if (in_array($this->getCode(), array('1000', '1012'))) {
			return $this->getData()['LoginResult']['SessionID'];
        }

        return null;
    }

    /**
     * Soap Request
     *
     * @return string — The last SOAP request, as an XML string.
     */
    public function getSoapRequest()
    {
        return $this->request->soap->__getLastRequest();
    }

    /**
     * Soap Response
     *
     * @return string — The last SOAP response, as an XML string.
     */
    public function getSoapResponse()
    {
        return $this->request->soap->__getLastResponse();
    }
}
