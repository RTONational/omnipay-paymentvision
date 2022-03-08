<?php

namespace Omnipay\PaymentVision\Message;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\PaymentVision\CommonParametersTrait;

/**
 * Send a request for details of a single transaction specified by transaction reference code
 */
class TransactionDetailsRequest extends AbstractRequest
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

        // set filter params
        $data['transactionFilter']['EnteredDate'] = null;
        $data['transactionFilter']['SettledDate'] = null;
        $data['transactionFilter']['SearchType'] = 'TransactionReferenceCode';
        $data['transactionFilter']['SearchValue'] = $this->getTransactionId();

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
        if (true === $this->getStubMode()) {
            $response = $this->getFakeResponse($data);
            return $this->response = new FakeResponse($this, $response);
        }

        if (!$this->soap) {
            $this->soap = new \SoapClient($this->getWsdl(), array('trace' => $this->getTestMode()));
        }
        
        $response = call_user_func_array(array($this->soap, 'GetTransactionDetails'), array($data));

        return $this->response = new Response($this, $response);
    }

    /**
     * Get fake response specific to this request
     *
     * @param  mixed $data The data that would otherwise be sent
     * @return object
     */
    public function getFakeResponse($data)
    {
        $dateString = date('YmdHis');
        $uuid = vsprintf('STUB%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(14)), 4));
        return (object) [
            'GetTransactionDetailsResult' => [
                'Responses' => [
                    'Response' => [
                        'ResponseCode' => '1000',
                        'ErrorMessage' => ''
                    ]
                ],
                'TimeReceived' => date('n/j/Y g:i:s A'),
                'RecordCount' => '1',
                'TransactionDetails' => [
                    'TransactionDetail' => [
                        'TransactionReferenceCode' => 'STUB' . $dateString,
                        'Status' => 'Cancelled',
                        'UserLogin' => 'RTO001-Admin',
                        'UserName' => 'RTO, Admin',
                        'PayeeCode' => 'RTOPay1',
                        'CustomerName' => 'Tester, Testy',
                        'CreditCardNumber' => 'Visa-XXXXXX1111',
                        'CardExpirationDate' => date('m/Y', strtotime('+1 year')),
                        'CardType' => 'Visa',
                        'RoutingNumber' => 'N/A',
                        'AccountNumber' => 'N/A',
                        'Fee' => '0.0000',
                        'TotalAmount' => '100.0000',
                        'DateEntered' => date('Y-m-d\TH:i:s.0000000'),
                        'DateSettled' => date('Y-m-d\TH:i:s.0000000'),
                        'PayeeName' => 'RTO Demo Payee1',
                        'GroupName' => 'Organization Administrators',
                        'ValidPayBatchName' => '',
                        'PostedDate' => date('Y-m-d\TH:i:s.0000000'),
                        'CustomerReferenceCode' => 'CU' . $dateString,
                        'AccountReferenceCode' => 'AC' . $dateString,
                        'MerchantName' => 'RTO Demo Merchant',
                        'MerchantCode' => 'MRTO001',
                        'UserDefinedFieldOne' => 'Testy Tester',
                        'UserDefinedFieldTwo' => '',
                        'ExternalRequestID' => $uuid,
                        'ConfirmationNumber' => 'API' . $dateString,
                        'CaptureTrackingNumber' => 'API' . $dateString,
                        'UserDefinedBatchName' => '',
                        'TransactionSeriesID' => '',
                        'SettlementType' => 'CreditCard',
                        'CVVResponse' => 'BAD',
                        'AVSResponse' => 'BAD',
                        'NetworkDeclineResponseCode' => '',
                        'NetworkAuthorizationResponseCode' => '820842',
                        'PaymentSeriesName' => '',
                        'PaymentOptionCode' => '',
                        'PaymentSeriesComment' => '',
                        'NetworkPaymentApplicationCode' => '',
                        'PrincipalAmount' => '100.0000',
                        'ReferenceID' => 'C' . $dateString,
                        'AccountType' => '',
                        'FullfillmentCode' => '',
                        'FullfillmentCodeExpirationDate' => '',
                        'CancelledReasonType' => 'APIVoidRequest',
                        'NameOnAccount' => 'Testy Tester',
                        'OriginalTransactionReferenceCode' => '',
                        'TransactionSequenceNumber' => '7iken0/34d4fi91IkB8K8Q==',
                    ]
                ]
            ]
        ];
    }
}
