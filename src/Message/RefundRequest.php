<?php

namespace Omnipay\PaymentVision\Message;

/**
 * Send a request for partially refunding a transaction
 */
class RefundRequest extends AbstractRequest
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

        $data['sessionID'] = $this->getSessionId();
        $data['transactionReferenceCode'] = $this->getTransactionId();
        $data['amount'] = $this->getAmount();
        $data['reason'] = $this->getRefundReason();

        return $data;
    }

    public function getRequestName() : string
    {
        return 'RefundTransaction';
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
            'RefundTransactionResult' => [
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => ''
                    ]
                ],
                'TimeReceived' => date('n/j/Y g:i:s A'),
            ]
        ];
    }
}
