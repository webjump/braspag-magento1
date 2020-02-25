<?php

class Braspag_Pagador_Block_Payment_Form_Method_CreditCard extends Braspag_Pagador_Block_Payment_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('braspag/pagador/payment/form/creditcard.phtml');

        $formChildren = $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassComposite('payment/braspag_creditcard/block/form');

        $this->processFormChildren($formChildren);
    }

    /**
     * @param string $child
     * @return Mage_Core_Block_Abstract|void
     */
    public function setChild($child)
    {
        parent::setChild($child->getBlockAlias(), $child->getBlockInstance());
    }

    /**
     * @return array
     */
    public function getCreditCardAvailableTypes()
    {
        if ($method = $this->getMethod()) {
            $types = $method->getCreditCardAvailableTypes();
        } else {
            $types = array();
        }

        return $types;
    }

    /**
     * @return bool
     */
    public function getInstallments()
    {
        if ($method = $this->getMethod()) {
            return $method->getInstallments();
        } else {
            return false;
        }
    }
}
