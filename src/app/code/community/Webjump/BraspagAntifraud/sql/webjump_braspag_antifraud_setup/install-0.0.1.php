<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$setupStatus = Mage::getModel('webjump_braspag_antifraud/setup_status')->getCollection();

foreach ($setupStatus AS $item) {
	Mage::getModel('sales/order_status')
		->setStatus($item->getStatus())
		->setLabel($item->getLabel())
		->assignState($item->getState())
		->save();
}

$table = $installer->getConnection()
    ->newTable($installer->getTable('webjump_braspag_antifraud/devicefingerprint'))
    ->addColumn('devicefingerprint_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    	), 'Device Finger Print ID')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
	    ), 'Real Order ID')
    ->addColumn('session_id', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
        'nullable' => false,
        ), 'Encrypted session id')
    ->addForeignKey(
        $installer->getFkName(
	        'webjump_braspag_antifraud/devicefingerprint',
	        'order_id',
	        'sales/order',
	        'entity_id'
    	),
    	'order_id',
    	$installer->getTable('sales/order'), 'entity_id',
    	Varien_Db_Ddl_Table::ACTION_CASCADE,
    	Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Webjump Braspag DeviceFingerPrint session');

$installer->getConnection()->createTable($table);


$table = $installer->getConnection()
    ->newTable($installer->getTable('webjump_braspag_antifraud/mdd'))
    ->addColumn('mdd_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    	), 'Mdd ID')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
	    ), 'Real Order ID')
    ->addColumn('additional_information', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Additional Information')
    ->addForeignKey(
        $installer->getFkName(
	        'webjump_braspag_antifraud/mdd',
	        'order_id',
	        'sales/order',
	        'entity_id'
    	),
    	'order_id',
    	$installer->getTable('sales/order'), 'entity_id',
    	Varien_Db_Ddl_Table::ACTION_CASCADE,
    	Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Webjump Braspag Mdd');

$installer->getConnection()->createTable($table);


$table = $installer->getConnection()
    ->newTable($installer->getTable('webjump_braspag_antifraud/antifraud'))
    ->addColumn('antifraud_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    	), 'Request ID')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
	    ), 'Real Order ID')
    ->addColumn('antifraud_transaction_id', Varien_Db_Ddl_Table::TYPE_TEXT, 36, array(
        'nullable' => false,
        ), 'Antifraud Transaction Id')
    ->addColumn('status_code', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable' => false,
        ), 'Status Code')
    ->addColumn('score', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable' => false,
        ), 'Score')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Updated At')
    ->addColumn('is_update_required', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable'  => false,
        'default'	=> 0,
	    ), 'Shows if require a update.')
    ->addColumn('is_manual_review', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable'  => false,
        'default'	=> 0,
    	), 'Shows if analysis was manually reviewed by store.')
    ->addForeignKey(
        $installer->getFkName(
	        'webjump_braspag_antifraud/antifraud',
	        'order_id',
	        'sales/order',
	        'entity_id'
    	),
    	'order_id',
    	$installer->getTable('sales/order'), 'entity_id',
    	Varien_Db_Ddl_Table::ACTION_CASCADE,
    	Varien_Db_Ddl_Table::ACTION_CASCADE)
	->addIndex(
		$installer->getIdxName('webjump_braspag_antifraud/antifraud', 'order_id', Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE),
		'order_id',
		array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
	->addIndex(
		$installer->getIdxName('webjump_braspag_antifraud/antifraud', 'status_code'),
        'status_code'
    )
	->addIndex(
		$installer->getIdxName('webjump_braspag_antifraud/antifraud', 'is_update_required'),
        'is_update_required'
    )
	->addIndex(
		$installer->getIdxName('webjump_braspag_antifraud/antifraud', 'is_manual_review'),
        'is_manual_review'
    )
    ->setComment('Antifraud');

$installer->getConnection()->createTable($table);


$table = $installer->getConnection()
    ->newTable($installer->getTable('webjump_braspag_antifraud/antifraud_detail'))
    ->addColumn('antifraud_detail_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    	), 'Request ID')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
	    ), 'Real Order ID')
    ->addColumn('antifraud_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
	    ), 'Antifraud ID')
    ->addColumn('antifraud_transaction_id', Varien_Db_Ddl_Table::TYPE_TEXT, 36, array(
        'nullable' => false,
        ), 'Antifraud Transaction Id')
    ->addColumn('status_code', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable' => false,
        ), 'Status Code')
    ->addColumn('score', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable' => false,
        ), 'Score')
    ->addColumn('additional_information', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Additional Information')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Created At')
    ->addForeignKey(
        $installer->getFkName(
	        'webjump_braspag_antifraud/antifraud_detail',
	        'order_id',
	        'sales/order',
	        'entity_id'
    	),
    	'order_id',
    	$installer->getTable('sales/order'), 'entity_id',
    	Varien_Db_Ddl_Table::ACTION_CASCADE,
    	Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $installer->getFkName(
	        'webjump_braspag_antifraud/antifraud_detail',
	        'antifraud_id',
	        'webjump_braspag_antifraud/antifraud',
	        'antifraud_id'
    	),
    	'antifraud_id',
    	$installer->getTable('webjump_braspag_antifraud/antifraud'), 'antifraud_id',
    	Varien_Db_Ddl_Table::ACTION_CASCADE,
    	Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Antifraud Detail');

$installer->getConnection()->createTable($table);

$installer->endSetup();
