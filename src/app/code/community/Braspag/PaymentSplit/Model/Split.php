<?php

/**
 * Class Braspag_PaymentSplit_Model_Payment_Split
 */
class Braspag_PaymentSplit_Model_Split extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('braspag_paymentsplit/split');
    }

    /**
     * @param $splitSubordinateData
     * @return $this
     * @throws Mage_Core_Model_Store_Exception
     */
    public function bind($splitSubordinateData)
    {
        $this->setSubordinateMerchantId($splitSubordinateData->getSubordinateMerchantId())
            ->setStoreMerchantId($splitSubordinateData->getStoreMerchantId())
            ->setTotalAmount($splitSubordinateData->getAmount())
            ->setStatus(self::BRASPAG_PAYMENT_SPLIT_STATUS_CREATED)
            ->setStoreId(Mage::app()->getStore()->getId());

        if (!empty($splitSubordinateData->getFares())) {
            $this->setMdrApplied(floatval($splitSubordinateData->getFares()->getMdr()));
            $this->setTaxApplied(floatval($splitSubordinateData->getFares()->getFee()));
        }

        return $this;
    }
}
