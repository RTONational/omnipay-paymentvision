<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\PaymentVision\Message\Response as MessageResponse;

class FakeResponse extends MessageResponse
{
    /**
     * Soap Request
     *
     * @return string — A message explaining that there was no SOAP request.
     */
    public function getSoapRequest()
    {
        return class_basename($this->request) . " Stubbed Request:\n" . print_r($this->getRequest()->getData(), true);
    }

    /**
     * Soap Response
     *
     * @return string — A message explaining that there was no SOAP response.
     */
    public function getSoapResponse()
    {
        return class_basename($this->request) . " Stubbed Response:\n" . print_r($this->getData(), true);
    }
}
