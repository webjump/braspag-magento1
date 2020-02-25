<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$tablePaymentSplitName = $installer->getTable('braspag_paymentsplit/payment_split');
$tablePaymentSplitItemName = $installer->getTable('braspag_paymentsplit/payment_split_item');

$installer->getConnection()->dropTable($tablePaymentSplitName);
$installer->getConnection()->dropTable($tablePaymentSplitItemName);

$tablePaymentSplit = $installer->getConnection()
    ->newTable($tablePaymentSplitName)
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ), 'Entity Id')
    ->addColumn('subordinate_merchant_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 36, array(
        'nullable' => false,
    ),
        'Subordinate Merchant Id')
    ->addColumn('store_merchant_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 36, array(
        'nullable' => true,
    ),
        'Store Merchant Id')
    ->addColumn('sales_quote_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned' => true,
            'nullable' => false,
        ), 'Sales Quote Id')
    ->addColumn('sales_order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned' => true,
            'nullable' => false,
        ), 'Sales Order Id')
    ->addColumn('mdr_applied', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable' => true,
        'default' => '0',
    ),
        'MDR Applied')
    ->addColumn('tax_applied', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4',
        array(
            'nullable' => true,
            'default' => '0',
        ), 'Tax Applied')
    ->addColumn('total_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4',
        array(
            'nullable' => true,
            'default' => '0',
        ), 'Total Amount')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned' => true,
            'nullable' => false,
        ), 'Store Id')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null,
        array(
            'nullable' => false,
            'default'  => Varien_Db_Ddl_Table::TIMESTAMP_INIT
        ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null,
        array(
            'nullable'  => true
        ), 'Updated At')
    ->setComment('Braspag Payment Split');

$installer->getConnection()->createTable($tablePaymentSplit);


$tablePaymentSplitItem = $installer->getConnection()
    ->newTable($tablePaymentSplitItemName)
    ->addColumn('split_item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ), 'Split Item Id')
    ->addColumn('split_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
        ), 'Split Id')
    ->addColumn('sales_quote_item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
        ), 'Sales Quote Item Id')
    ->addColumn('sales_order_item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned' => true,
            'nullable' => false,
            'default' => '0',
        ), 'Sales Order Item Id')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null,
        array(
            'nullable' => false,
            'default'  => Varien_Db_Ddl_Table::TIMESTAMP_INIT
        ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null,
        array(
            'nullable'  => true
        ), 'Updated At')
    ->setComment('Braspag Payment Split Item');

$installer->getConnection()->createTable($tablePaymentSplitItem);

$installer->endSetup();