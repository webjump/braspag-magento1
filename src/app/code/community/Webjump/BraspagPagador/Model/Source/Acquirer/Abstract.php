<?php
class Webjump_BraspagPagador_Model_Source_Acquirer_Abstract
{
	protected $_options;

    protected function getConfig()
    {
        return Mage::getSingleton('webjump_braspag_pagador/config');
    }

    public function toOptionArray()
    {
        if(!$this->_options){

            $acquirers = $this->getConfig()->getAcquirers();
            $ccAcquirers = $this->getPaymentMethods();

            $this->_options = array();
            foreach($ccAcquirers AS $acquirerKey => $brands){

                foreach ($brands as $brand) {
                    $this->_options[] = array(
                        'value' => (empty($acquirers[$acquirerKey]) ? '' : $acquirerKey."-").$brand,
                        'label' =>  (empty($acquirers[$acquirerKey]) ? '' : $acquirers[$acquirerKey]." - ").$brand,
                    );
                }

            }
        }

        return $this->_options;
    }
}