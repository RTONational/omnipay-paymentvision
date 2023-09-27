<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\PaymentVision\CommonParametersTrait;

abstract class AbstractRequest extends BaseAbstractRequest
{
    use CommonParametersTrait;

    /**
     * @var \SoapClient
     */
    protected $soap = null;

    public function getSoap()
    {
        return $this->soap;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data) : ResponseInterface
    {
        if (true === $this->getStubMode()) {
            $responseData = $this->getFakeResponse($data);
            return $this->response = new FakeResponse($this, $responseData);
        }

        if (!$this->soap) {
            $this->soap = new \SoapClient($this->getWsdl(), array('trace' => $this->getTestMode()));
        }

        $responseData = call_user_func_array(array($this->soap, $this->getRequestName()), array($data));

        return $this->response = $this->makeResponse($responseData);
    }

    public abstract function getRequestName() : string;

    /**
     * Get fake response specific to this request
     *
     * @param  mixed $data The data that would otherwise be sent
     * @return object
     */
    abstract public function getFakeResponse($data);

    /**
     * @param mixed $data
     */
    protected function makeResponse($data) : ResponseInterface
    {
        return new Response($this, $data);
    }
}
