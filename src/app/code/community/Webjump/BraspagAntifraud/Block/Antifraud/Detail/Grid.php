<?php

class Webjump_BraspagAntifraud_Block_Antifraud_Detail_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('antifraud_details');
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare collection for grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = ($this->getCollection())
        ? $this->getCollection() : Mage::getResourceModel('webjump_braspag_antifraud/antifraud_detail_collection');
        $order = Mage::registry('current_order');
        if ($order) {
            $collection->addOrderIdFilter($order->getId());
        }
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'antifraud_detail_id',
            array(
                'header' => Mage::helper('sales')->__('ID #'),
                'index' => 'antifraud_detail_id',
                'type' => 'string',
            )
        );

        $this->addColumn(
            'order_id',
            array(
                'header' => Mage::helper('sales')->__('Order ID'),
                'index' => 'order_id',
                'type' => 'string',
            )
        );

        $this->addColumn(
            'antifraud_transaction_id',
            array(
                'header' => Mage::helper('sales')->__('transaction ID'),
                'index' => 'antifraud_transaction_id',
                'type' => 'string',
            )
        );

        $this->addColumn(
            'status_code',
            array(
                'header' => Mage::helper('sales')->__('Status'),
                'index' => 'status_code',
                'type' => 'options',
                'options' => Mage::helper('webjump_braspag_antifraud')->getStatus(),
            )
        );

        $this->addColumn(
            'score',
            array(
                'header' => Mage::helper('sales')->__('Score'),
                'index' => 'score',
                'type' => 'string',
            )
        );

        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('sales')->__('Created at'),
                'index' => 'created_at',
                'type' => 'string',
            )
        );

        return parent::_prepareColumns();
    }

    /**
     * Retrieve grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    /**
     * Retrieve row url
     *
     * @return string
     */
    public function getRowUrl($item)
    {
        return $this->getUrl('*/webjump_braspag_antifraud/view', array('detail_id' => $item->getAntifraudDetailId()));
    }
}
