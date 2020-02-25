<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

/**
 * DO migration from justclick table to protected card if justclick table exists
 */

$table = $installer->getConnection()
   ->newTable($installer->getTable('braspag_protectedcard/card'))
   ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
       array(
           'identity' => true,
           'unsigned' => true,
           'nullable' => false,
           'primary' => true,
       ), 'Entity Id')
   ->addColumn('alias', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
       'nullable' => false,
   ),
       'Credit Card Alias')
   ->addColumn('token', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
       'nullable' => false,
   ),
       'Credit Card Token')
   ->addColumn('expiration_date', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
       'nullable' => true,
   ),
       'Credit Card ExpirationDate')
   ->addColumn('provider', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
       'nullable' => false,
   ),
       'Payment Provider')
   ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
       array(
           'unsigned' => true,
           'nullable' => false,
           'default' => '0',
       ), 'Customer Id')
   ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_BOOLEAN, 0,
       array(
           'nullable' => false,
           'default' => 0,
       ), 'Status')
   ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
       array(
           'unsigned' => true,
           'nullable' => false,
       ), 'Store Id')
   ->addForeignKey(
        $installer->getFkName(
            'braspag_protectedcard/card',
            'customer_id',
            'customer/entity',
            'entity_id'
        ),
       'customer_id',
       $installer->getTable('customer/entity'), 'entity_id',
       Varien_Db_Ddl_Table::ACTION_CASCADE,
       Varien_Db_Ddl_Table::ACTION_CASCADE)
   ->setComment('Customer Protected Credit Cards');

$installer->getConnection()->createTable($table);
$installer->endSetup();