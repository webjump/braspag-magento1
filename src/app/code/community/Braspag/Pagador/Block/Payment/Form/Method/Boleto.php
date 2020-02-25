<?php
class Braspag_Pagador_Block_Payment_Form_Method_Boleto extends Braspag_Pagador_Block_Payment_Form
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('braspag/pagador/payment/form/boleto.phtml');

        $formChildren = $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassComposite('payment/braspag_boleto/block/form');

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

    public function getPaymentInstructions()
    {
        $method = $this->getMethod();
        return $method->getConfigData('payment_instructions');
    }

    public function getBoletoType()
    {
        $method = $this->getMethod();
        return $method->getConfigData('boleto_type');
    }
}
