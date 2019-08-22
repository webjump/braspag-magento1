<?php

class Webjump_BraspagAntifraud_Block_Adminhtml_Sales_Antifraud_Detail_Tree extends Mage_Adminhtml_Block_Widget
{
    const SPECIAL_ATTRIBUTES_DELIMITER = '^';
    protected $specialAttributes = array(
        'InternetInfoCode',
        'AddressInfoCode',
        'AfsFactorCode',
        'ReasonCode',
        'SuspiciousInfoCode',
        'VelocityInfoCode',
    );

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        $this->setTemplate('webjump/braspag_antifraud/sales/antifraud/detail/tree.phtml');
        $this->_emptyText = Mage::helper('adminhtml')->__('No records found.');
    }

    public function getDetail()
    {
        if ($detail = Mage::registry('current_antifraud_detail')) {
            return $detail;
        }

        return false;
    }

    public function getTreeHtml()
    {
        if ($detail = $this->getDetail()) {
            return $this->convertArrayToRecurseUlTree($detail->getData('additional_information'));
        }

        return $this->_emptyText;
    }

    protected function convertArrayToRecurseUlTree($attribute)
    {
        $out = "<ul>";

        foreach ($attribute as $label => $value) {
            if (is_object($value)) {
                $value = $this->convertArrayToRecurseUlTree(get_object_vars($value));
            } elseif (is_array($value)) {
                $value = $this->convertArrayToRecurseUlTree($value);
            } elseif (in_array($label, $this->getSpecialAttributes())) {
                $value = $this->processSpecialAttibutes($label, $value);
                $value = $this->convertArrayToRecurseUlTree($value);
            }

            $out .= "<li><span><strong>$label</strong>:  $value</span></li>";
        }

        return $out . "</ul>";
    }

    protected function processSpecialAttibutes($label, $attributes)
    {
        $attributes = explode(self::SPECIAL_ATTRIBUTES_DELIMITER, $attributes);
        $attributes = array_combine($attributes, $attributes);
        $return = array();

        foreach ($attributes as $value) {
            $return[$value] = Mage::helper('webjump_braspag_antifraud')->__(((string) $label . '.' . $value));
        }

        return $return;
    }

    protected function getSpecialAttributes()
    {
        return $this->specialAttributes;
    }
}
