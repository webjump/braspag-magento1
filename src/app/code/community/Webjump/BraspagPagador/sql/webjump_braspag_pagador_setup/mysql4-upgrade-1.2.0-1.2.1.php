<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$statuses = [
    'wj_braspag_fraud_approved',
    'wj_braspag_fraud_rejected',
    'wj_braspag_fraud_review',
    'wj_braspag_fraud_error'
];

foreach ($statuses AS $status) {
    $statusToDelete = Mage::getModel('sales/order_status')
        ->getCollection()
        ->addFieldToFilter('status', $status)
        ->getFirstItem();

    if($statusToDelete->getStatus()) {
        $statusToDelete->delete();
    }
}

$installer->endSetup();