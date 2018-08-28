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
}