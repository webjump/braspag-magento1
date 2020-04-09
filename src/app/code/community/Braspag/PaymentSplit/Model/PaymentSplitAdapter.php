<?php

/**
 * Class Braspag_PaymentSplit_Model_Payment_SplitAdapter
 */
class Braspag_PaymentSplit_Model_PaymentSplitAdapter extends Mage_Core_Model_Abstract
{
    /**
     * @param array $splitPaymentData
     * @return Varien_Object
     */
    public function adapt(array $splitPaymentData)
    {
        $dataSplitPayments = new Varien_Object();
        $splitPayments = [];
        foreach ($splitPaymentData as $subordinateId => $subordinate) {

            $subordinateDataObject = new Varien_Object();
            $subordinateFaresDataObject = new Varien_Object();

            $subordinateFaresData = [
                "mdr" => floatval(isset($subordinate['Fares']['Mdr']) ?
                    $subordinate['Fares']['Mdr'] : $subordinate['fares']['mdr']
                ),
                "fee" => intval(isset($subordinate['Fares']['Fee']) ?
                    $subordinate['Fares']['Fee'] : $subordinate['fares']['fee']
                )
            ];

            $subordinateFaresDataObject->addData($subordinateFaresData);

            $merchantId = Mage::getSingleton('braspag_core/config_general')
                ->getMerchantId();

            $subordinateMerchantId = isset($subordinate['SubordinateMerchantId']) ?
                $subordinate['SubordinateMerchantId'] : $subordinateId;

            $subordinateData = [
                "subordinate_merchant_id" => $subordinateMerchantId,
                "store_merchant_id" => strtolower($merchantId),
                "amount" => isset($subordinate['Amount']) ?
                    $subordinate['Amount'] : $subordinate['amount'],
                "fares" => $subordinateFaresDataObject
            ];

            if (isset($subordinate['items'])) {
                $subordinateData['items'] = $subordinate['items'];
            }

            $subordinateData['splits'] = [];

            if (isset($subordinate['Splits'])) {
                $subordinateData['splits'] = $this->adaptSplits($subordinate['Splits']);
            }

            $subordinateDataObject->addData($subordinateData);

            $splitPayments['subordinates'][] = $subordinateDataObject;
            $splitPayments['store_merchant_id'] = strtolower($merchantId);
        }

        $dataSplitPayments->addData($splitPayments);

        return $dataSplitPayments;
    }

    /**
     * @param $subordinateSplits
     * @return array
     */
    protected function adaptSplits($subordinateSplits)
    {
        $subordinateSplitsData = [];
        foreach ($subordinateSplits as $split) {
            $splitObject = new Varien_Object();
            $subordinateSplitsData[] = $splitObject->addData([
                "merchant_id" => $split['MerchantId'],
                "amount" => $split['Amount']
            ]);
        }

        return $subordinateSplitsData;
    }
}
