<?php
class Braspag_Pagador_Block_Payment_Form_Method_DebitCard extends Braspag_Pagador_Block_Payment_Form
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('braspag/pagador/payment/form/debitcard.phtml');

        $formChildren = $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassComposite('payment/braspag_debitcard/block/form');

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

	public function getDebitCardAvailableTypes()
	{
        if ($method = $this->getMethod()) {
	        $types = $method->getDebitCardAvailableTypes();
        }
        else{
			$types = array();
        }
        return $types;
	}	
}
