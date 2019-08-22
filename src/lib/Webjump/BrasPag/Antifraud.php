<?php
abstract class Webjump_BrasPag_Antifraud extends Varien_Object
{
    public $client;

    public $_data = array(
        'version' => '1.1',
        'anti_fraud_sequence_type' => 'AnalyseOnly',
    );

    public function __construct($wsdl = false)
    {
        $client = new Webjump_WSSoapClientMage($wsdl, array(
            'trace' => 1,
            'exceptions' => 1,
        ));

        $this->client = $client;
        return $this;
    }

    public function generateGuid($orderIncrementId)
    {
        $orderIncrementId = preg_replace('/[^0-9]/', '0', $orderIncrementId);
        $hash = strtoupper(hash('ripemd128', uniqid('', true) . md5(time() . rand(0, time()))));
        $guid = substr($hash, 0, 8) . '-' . substr($hash, 8, 4) . '-' . substr($hash, 12, 4) . '-' . substr($hash, 16, 4) . '-' . str_pad($orderIncrementId, 12, '0', STR_PAD_LEFT);

        return $guid;
    }

    abstract public function prepareDataToReview();

    public function reviewOrder() {
        try {
            $data = $this->getPreparedReviewData();
            $this->client->__allowEmptyTags(false);
            $response = $this->client->fraudAnalysis($data);
            $this->setResponse($response);
        } catch (SoapFault $fault) {
            throw new Exception($fault->faultstring);
        }
    }

    public function updateStatus()
    {
        try {
            $data = $this->getPreparedUpdateStatusData();
            $this->client->__allowEmptyTags(false);
            $response = $this->client->updateStatus($data);
            $this->setResponse($response);
        } catch (SoapFault $fault) {
            throw new Exception($fault->faultstring);
        }
    }

    public function updateReviewOrder()
    {
        try {
            $data = $this->getPreparedUpdateReviewOrderData();
            $this->client->__allowEmptyTags(false);
            $response = $this->client->fraudAnalysisTransactionDetails($data);
            $this->setResponse($response);
        } catch (SoapFault $fault) {
            throw new Exception($fault->faultstring);
        }
    }
}
