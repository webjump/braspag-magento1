<?php
class Braspag_Pagador_Block_Payment_Info extends Mage_Payment_Block_Info
{
    protected $specificInformation = [];
    protected $compositeChildren = [];
    protected $_installments = null;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('braspag/pagador/payment/info/default.phtml');
    }

    /**
     * @param array $blockChildren
     * @return $this
     */
    protected function processInfoChildren(array $blockChildren)
    {
        $blockComposite = Mage::getModel('braspag_pagador/block_composite');

        foreach ($blockChildren as $child) {
            $blockComposite->addChild($child);
        }

        $blockComposite->processBlock($this);

        return $blockComposite;
    }

    /**
     * @param $child
     */
    public function addCompositeChild($child)
    {
        $this->compositeChildren[] = $child;
    }

    /**
     * @return array
     */
    public function getCompositeChildren()
    {
        return $this->compositeChildren;
    }

    /**
     * @param string $child
     * @return Mage_Core_Block_Abstract|void
     */
    public function setChild($child)
    {
        parent::setChild($child->getBlockAlias(), $child->getBlockInstance());

        $this->addCompositeChild($child);
    }

    /**
     * @param $paymentRequest
     * @return array
     */
    public function getCompositeChildrenSpecificInfo($paymentRequest)
    {
        $specificInformation = [];
        foreach($this->getCompositeChildren() as $compositeChild) {
            $specificInformation = array_merge($specificInformation, $this->getCompositeChildSpecificInfo($compositeChild, $paymentRequest));
        }

        return $specificInformation;
    }

    protected function getCompositeChildSpecificInfo($compositeChild, $paymentRequest)
    {
        $specificInformation = [];
        foreach ($compositeChild->getSpecificInformation($paymentRequest) as $information) {
            $specificInformation[] = $information;
        }
        return $specificInformation;
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    public function getBraspagCoreConfigHelper()
    {
        return Mage::helper('braspag_core/config');
    }
}
