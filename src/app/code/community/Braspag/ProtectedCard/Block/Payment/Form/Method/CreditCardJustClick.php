<?php

class Braspag_ProtectedCard_Block_Payment_Form_Method_CreditCardJustClick
    extends Braspag_Pagador_Block_Payment_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('braspag/protectedcard/payment/form/justclick.phtml');

        $formChildren = $this->getBraspagCoreConfigHelper()
            ->getDefaultConfigClassComposite('payment/braspag_justclick/block/form');

        $this->processFormChildren($formChildren);
    }

    /**
     * @return mixed
     */
    public function getAvailableCards()
    {
        return Mage::getModel('braspag_protectedcard/card')
            ->loadCardsByCustomer(Mage::helper('customer')->getCustomer());
    }

    /**
     * @return mixed
     */
    public function isShowCardVerifyCode()
    {
        $method = $this->getMethod();
        return $method->getConfigData('cvv_required');
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
