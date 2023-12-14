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
     * Gateway Transaction Id
     *
     * @return null|string An id provided by PaymentVision to represent this transaction
     */
    public function getTransactionId()
    {
        $transactionId = null;

        array_walk_recursive($this->data, function ($val, $key) use (&$transactionId) {
            if ($key == 'TransactionReferenceCode') {
                $transactionId = $val;
            }
        });

        return $transactionId;
    }

    /**
     * Gateway Reference Id
     *
     * @return null|string A reference id provided by PaymentVision to represent an account
     */
    public function getReferenceId()
    {
        $referenceId = null;

        array_walk_recursive($this->data, function ($val, $key) use (&$referenceId) {
            if ($key == 'ReferenceID') {
                $referenceId = $val;
            }
        });

        return $referenceId;
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
        return $this->request->getSoap()->__getLastRequest();
    }

    /**
     * Soap Response
     *
     * @return string — The last SOAP response, as an XML string.
     */
    public function getSoapResponse()
    {
        return $this->request->getSoap()->__getLastResponse();
    }

    /**
     * AVS Response
     *
     * @return string
     */
    public function getAvsResponse()
    {
        // AVSResponse Codes are as follows:
        // X = Address/9 digit zip match
        // Y = Address/5 digit zip match
        // A = Address matches, zip does not
        // W = Address does not match, 9 digit zip matches
        // Z = Address does not match, 5 digit zip matches
        // N = Address/zip do not match
        // U = Address unavailable
        // R = Retry (system unavailable or timed out)
        // E = Error (AVS data invalid)
        // S = Service not supported

        $avsCode = null;

        array_walk_recursive($this->data, function ($val, $key) use (&$avsCode) {
            if ($key == 'AVSResponse') {
                $avsCode = $val;
            }
        });

        return $avsCode;
    }
}
