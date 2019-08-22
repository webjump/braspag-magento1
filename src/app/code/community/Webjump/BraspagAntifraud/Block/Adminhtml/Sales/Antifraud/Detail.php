<?php

class Webjump_BraspagAntifraud_Block_Adminhtml_Sales_Antifraud_Detail extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * Transaction model
     *
     * @var Mage_Sales_Model_Order_Payment_Transaction
     */
    protected $_detail;

    /**
     * Add control buttons
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->_detail = Mage::registry('current_antifraud_detail');

        $backUrl = ($this->_detail->getOrderUrl()) ? $this->_detail->getOrderUrl() : $this->getUrl('*/*/');
        $this->_addButton('back', array(
            'label' => Mage::helper('webjump_braspag_antifraud')->__('Back'),
            'onclick' => "setLocation('{$backUrl}')",
            'class' => 'back',
        ));
    }

    public function getHeaderText()
    {
        return Mage::helper('webjump_braspag_antifraud')->__("Detail # %s | %s", $this->_detail->getAntifraudDetailId(), $this->formatDate($this->_detail->getCreatedAt(), Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM, true));
    }

    public function _toHtml()
    {
        $this->setIdHtml(
            $this->escapeHtml($this->_detail->getAntifraudDetailId())
        );

        $this->setOrderIncrementIdHtml(
            $this->escapeHtml($this->_detail->getOrderId())
        );

        $this->setOrderIdUrlHtml(
            $this->escapeHtml($this->getUrl('*/sales_order/view', array('order_id' => $this->_detail->getOrderId())))
        );

        $this->setTransactionIdHtml(
            $this->escapeHtml($this->_detail->getAntifraudTransactionId())
        );

			$this->setStatusHtml(
            $this->escapeHtml(
                Mage::helper('webjump_braspag_antifraud')->getStatusLabelByCode($this->_detail->getStatusCode())
            )
        );

        $this->setScoreHtml(
            $this->escapeHtml($this->_detail->getScore())
        );

        $createdAt = (strtotime($this->_detail->getCreatedAt()))
        ? $this->formatDate($this->_detail->getCreatedAt(), Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM, true)
        : $this->__('N/A');
        $this->setCreatedAtHtml($this->escapeHtml($createdAt));

        return parent::_toHtml();
    }
}
