<?php
class Webjump_BraspagAntifraud_Model_Api extends Webjump_BrasPag_Antifraud
{
    const STATUS_STARTED = 500;
    const STATUS_ACCEPT = 501;
    const STATUS_REVIEW = 502;
    const STATUS_REJECT = 503;
    const STATUS_PENDENT = 504;
    const STATUS_UNFINISHED = 505;
    const STATUS_ABORTED = 506;

	const ERROR_CAN_CHANGE_ONLY_REVIEW = 113;

    protected $_hlp;

    public function __construct()
    {
        $environment = $this->getHelper()->getEnvironment();
        $wsdl = Mage::getStoreConfig('webjump_braspag_antifraud/webservice/antifraud/url/' . $environment);

        parent::__construct($wsdl);
    }

    protected function getHelper()
    {
        if (!$this->_hlp) {
            $this->_hlp = Mage::helper('webjump_braspag_antifraud');
        }

        return $this->_hlp;
    }

    protected function debugSoap($client)
    {
        $this->getHelper()->debug(array(
            'request' => $client->__getLastRequest(),
            'response' => $client->__getLastResponse(),
        ));
    }

    public function getCustomerDob()
    {
        $customerDob = $this->getOrder()->getCustomerDob();

        return date('Y-m-d', strtotime($customerDob));
    }

    public function getShippingMethod()
    {
        $order = $this->getOrder();
        $orderShippingMethod = $order->getShippingMethod();
        $antifraudShippingMethods = unserialize(Mage::getStoreConfig('webjump_braspag_antifraud/configuration/shipping_method', $order->getStoreId()));
        foreach ($antifraudShippingMethods as $key => $value) {
            if ($value['magento'] == $orderShippingMethod) {
                return $value['antifraud'];
            }
        }

        return Webjump_BraspagAntifraud_Model_Config::SHIPPING_METHOD_OTHER;
    }

    public function getGuid()
    {
        return $this->generateGuid($this->getOrder()->getIncrementId());
    }

    public function getDeviceFingerPrintId()
    {
        return Mage::getModel('webjump_braspag_antifraud/devicefingerprint')->getOrderSessionId($this->getOrder());
    }

    public function getDocumentTaxvat()
    {
        $config = Mage::getStoreConfig('webjump_braspag_antifraud/configuration/taxvat_field');
        /* @var $filter Mage_Cms_Model_Template_Filter */
        $filter = Mage::getModel('cms/template_filter');
        $filter->setVariables(array('order' => $this->getOrder()));

        return preg_replace('/[^0-9]/', '', $filter->filter($config));
    }

    public function getDocumentCpf()
    {
        $documentTaxvat = $this->getDocumentTaxvat();
        if (Mage::helper('webjump_braspag_antifraud/validate')->isCpf($documentTaxvat)) {
            return $documentTaxvat;
        }
    }

    public function getDocumentCnpj()
    {
        $documentTaxvat = $this->getDocumentTaxvat();
        if (Mage::helper('webjump_braspag_antifraud/validate')->isCnpj($documentTaxvat)) {
            return $documentTaxvat;
        }
    }

    public function getDocumentOther()
    {
        $documentTaxvat = $this->getDocumentTaxvat();
        if (!Mage::helper('webjump_braspag_antifraud/validate')->isCpfOrCnpj($documentTaxvat)) {
            return $documentTaxvat;
        }
    }

    public function prepareDataToReview()
    {
        $order = $this->getOrder();
        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();

        $this->client->__setVariables(array(
            'antifraud' => $this,
            'order' => $order,
            'billingAddress' => $billingAddress,
            'shippingAddress' => $shippingAddress,
        ));

        $data = Mage::getStoreConfig('webjump_braspag_antifraud/webservice/antifraud/request_map/fraudAnalysis');

        if (!empty($data['request']['AntiFraudRequest']['ItemDataCollection']['ItemData'])) {
            $itemData = $data['request']['AntiFraudRequest']['ItemDataCollection']['ItemData'];
            $data['request']['AntiFraudRequest']['ItemDataCollection']['ItemData'] = array();

            foreach ($order->getAllVisibleItems() as $key => $item) {
                $data['request']['AntiFraudRequest']['ItemDataCollection']['ItemData'][] = $this->filterItemData($item, $itemData);
            }
        }

        $this->setPreparedReviewData((object) $data);

        return $this;
    }

    public function updateReviewOrder()
    {
        try {

            $this->preparedUpdateReviewOrderData();

            Mage::dispatchEvent('webjump_braspag_antifraud_update_review_prepare_data_after', array(
                'review_data' => $this->preparedUpdateReviewOrderData(), 'order' => $this->getOrder(),
            ));

            $this->client->__setTimeout(Mage::getStoreConfig('webjump_braspag_antifraud/webservice/antifraud/timeout'));
            parent::updateReviewOrder();

        } catch (Exception $e) {
            $hlp = $this->getHelper();
            $hlp->debug('Error while review order. ' . $e->getMessage() . '(error code ' . $e->getCode() . ')');

            $this->debugSoap($this->client);

            throw new Exception($hlp->__('Antifraud - Error while review order. %s', $hlp->__($e->getMessage())), $e->getCode());
        }

        $this->debugSoap($this->client);

        return true;
    }

    public function preparedUpdateReviewOrderData()
    {
        $order = $this->getOrder();

        $this->client->__setVariables(array(
            'antifraud' => $this,
            'order' => $order,
        ));

        $data = Mage::getStoreConfig('webjump_braspag_antifraud/webservice/antifraud/request_map/updateReviewOrder');
        $this->setPreparedUpdateReviewOrderData((object) $data);

        return $this;
    }

    protected function filterItemData($item, $itemData)
    {
        /* @var $filter Mage_Cms_Model_Template_Filter */
        $filter = Mage::getModel('cms/template_filter');
        $filter->setVariables(array('item' => $item));

        $data = array();
        foreach ($itemData as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->filterItemData($item, $value);
            } else {
                $data[$key] = $filter->filter($value);
            }
        }

        return $data;
    }

    public function getErrorReportCollectionAsString($resultNode)
    {
        $antifraudResponse = $this->getResponse();
        $hlp = $this->getHelper();
        $msg = array();

        //if not have ErrorReport
        if (!property_exists($antifraudResponse->$resultNode->ErrorReportCollection, 'ErrorReport')) {
            $msg[] = $hlp->__('Error: %s (code %s).', $hlp->__($antifraudResponse->$resultNode->TransactionStatusDescription), $antifraudResponse->$resultNode->TransactionStatusCode);
        } else {
            //If returns only a error...
            if ($antifraudResponse->$resultNode->ErrorReportCollection->ErrorReport instanceof stdClass) {
                $antifraudResponse->$resultNode->ErrorReportCollection->ErrorReport = array($antifraudResponse->$resultNode->ErrorReportCollection->ErrorReport);
            }
            foreach ($antifraudResponse->$resultNode->ErrorReportCollection->ErrorReport as $errorReport) {
                $msg[] = $hlp->__('Error: %s (code %s).', $hlp->__($errorReport->ErrorMessage), $errorReport->ErrorCode);
            }
        }

        return '<ul><li>' . implode('</li><li>', $msg) . '</li></ul>';
    }

    public function getErrorReportCollectionAsCode($resultNode)
    {
        $antifraudResponse = $this->getResponse();
        $hlp = $this->getHelper();
        $msg = array();

        //if not have ErrorReport
        if (!property_exists($antifraudResponse->$resultNode->ErrorReportCollection, 'ErrorReport')) {
            $msg[] = $antifraudResponse->$resultNode->TransactionStatusCode;
        } else {
            //If returns only a error...
            if ($antifraudResponse->$resultNode->ErrorReportCollection->ErrorReport instanceof stdClass) {
                $antifraudResponse->$resultNode->ErrorReportCollection->ErrorReport = array($antifraudResponse->$resultNode->ErrorReportCollection->ErrorReport);
            }
            foreach ($antifraudResponse->$resultNode->ErrorReportCollection->ErrorReport as $errorReport) {
                $msg[] = $errorReport->ErrorCode;
            }
        }

        return implode(',', $msg);
    }

    public function reviewOrder()
    {
        try {

            $this->prepareDataToReview();

            $storeId = $this->getOrder()->getStoreId();

            Mage::dispatchEvent('webjump_braspag_antifraud_review_prepare_data_after', array(
                'review_data' => $this->getPreparedReviewData(), 'order' => $this->getOrder(),
            ));

            $this->client->__setTimeout(Mage::getStoreConfig('webjump_braspag_antifraud/webservice/antifraud/timeout'));
            parent::reviewOrder();

            $antifraudResponse = $this->getResponse();

            if (!$antifraudResponse->FraudAnalysisResult ||
                (
                    !$antifraudResponse->FraudAnalysisResult->Success &&
                    $antifraudResponse->FraudAnalysisResult->TransactionStatusCode != self::STATUS_REJECT
                )
            ) {
                $errorMessage = $this->getErrorReportCollectionAsString('FraudAnalysisResult');
                $errorCode = $this->getErrorReportCollectionAsCode('FraudAnalysisResult');
                throw new Exception($errorMessage);
            }

        } catch (Exception $e) {
            $hlp = $this->getHelper();
            $hlp->debug('Error while review order. ' . $e->getMessage() . '(error code ' . $e->getCode() . ')');

            $this->debugSoap($this->client);

            throw new Exception($hlp->__('Antifraud - Error while review order. %s', $hlp->__($e->getMessage())), $e->getCode());
        }

        $this->debugSoap($this->client);

        return true;
    }

    public function prepareDataToUpdateStatus()
    {
        $order = $this->getOrder();
        $newStatus = $this->getNewStatus();
        $antifraud = $this->getAntifraud();

        $this->client->__setVariables(array(
            'antifraud' => $this,
            'order' => $order,
        ));

        $data = Mage::getStoreConfig('webjump_braspag_antifraud/webservice/antifraud/request_map/updateStatus');
        $this->setPreparedUpdateStatusData((object) $data);

        return $this;
    }

    public function updateStatus()
    {
        try {

            $this->prepareDataToUpdateStatus();

            Mage::dispatchEvent('webjump_braspag_antifraud_update_status_prepare_data_after', array(
                'update_status_data' => $this->getPreparedUpdateStatusData(), 'order' => $this->getOrder(), 'antifraud' => $this->getAntifraud(),
            ));

            $this->client->__setTimeout(Mage::getStoreConfig('webjump_braspag_antifraud/webservice/antifraud/timeout'));
            parent::updateStatus();

            $antifraudResponse = $this->getResponse();

            if (!$antifraudResponse->UpdateStatusResult || !$antifraudResponse->UpdateStatusResult->Success) {
                $errorMessage = $this->getErrorReportCollectionAsString('UpdateStatusResult');
                $errorCode = $this->getErrorReportCollectionAsCode('UpdateStatusResult');
                throw new Exception($errorMessage, $errorCode);
            }

        } catch (Exception $e) {
            $hlp = $this->getHelper();
            $hlp->debug('Error while update status. ' . $e->getMessage() . '(error code ' . $e->getCode() . ')');

            $this->debugSoap($this->client);

            throw new Exception($hlp->__('Antifraud - Error while update status. %s', $hlp->__($e->getMessage())), $e->getCode());
        }

        $this->debugSoap($this->client);

        return true;
    }
}

class Webjump_Antifraud_Checksum
{
    // Used used binaray in Hex format
    private $privateKey = "ec340029d65c7125783d8a8b27b77c8a0fcdc6ff23cf04b576063fd9d1273257"; // default
    private $keySize = 32;
    private $profile;
    private $hash = "sha1";

    public function __construct($option, $key = null, $hash = "sha1")
    {
        $this->profile = $this->_normalizeOption($option);
        $this->hash = $hash;

        // Use Default Binary Key or generate yours
        $this->privateKey = ($key === null) ? pack('H*', $this->privateKey) : $key;
        $this->keySize = strlen($this->privateKey);
    }

    //Normalize new lines since Magento Cache system modify them
    private function _normalizeOption($value)
    {
        foreach ($value AS $k => $v) {
            $value[$k] = preg_replace('/[\r\n]{1,}/', PHP_EOL, $v);
        }
        return $value;
    }

    private function randString($length)
    {
        $r = 0;
        switch (true) {
            case function_exists("openssl_random_pseudo_bytes"):
                $r = bin2hex(openssl_random_pseudo_bytes($length));
                break;
            case function_exists("mcrypt_create_ivc"):
            default:
                $r = bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
                break;
        }

        return strtoupper(substr($r, 0, $length));
    }

    public function generate($keys = false)
    {
        // 10 ramdom char
        $keys = $keys ?: $this->randString(10);
        $keys = strrev($keys); // reverse string

        // Add keys to options
        if (is_array($this->profile)) {
            $this->profile['keys'] = $keys;
        } else {
            $this->profile->keys = $keys;
        }

        // Serialise to convert to string
        $data = json_encode($this->profile);

        // Simple Random Chr authentication
        $hash = hash_hmac($this->hash, $data, $this->privateKey);
        $hash = str_split($hash);

        $step = floor(count($hash) / 15);
        $i = 0;

        $key = array();
        foreach (array_chunk(str_split($keys), 2) as $v) {
            $i = $step + $i;
            $key[] = sprintf("%s%s%s%s%s", $hash[$i++], $v[1], $hash[$i++], $v[0], $hash[$i++]);
            $i++; // increment position
        }

        return strtoupper(implode("-", $key));
    }

    public function check($key)
    {
        $key = trim($key);
        if (strlen($key) != 29) {
            return false;
        }
        // Exatact ramdom keys
        $keys = implode(array_map(function ($v) {
            return $v[3] . $v[1];
        }, array_map("str_split", explode("-", $key))));

        $keys = strrev($keys); // very important

        return $key === $this->generate($keys);
    }
}